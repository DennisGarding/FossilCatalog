<?php

namespace App\Controller\Installation;

use App\Installation\CreateUserService;
use App\Installation\EarthAges\Series;
use App\Installation\EarthAges\Stage;
use App\Installation\EarthAges\System;
use App\Static\Installation\DotEnvFile;
use App\Static\Installation\Installation;
use App\Static\Installation\InstallationData;
use App\Static\Installation\LockFile;
use App\Static\Installation\PDOConnection;
use App\Static\Validation\ValidationResult;
use App\Translations\TranslationService;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class InstallationController extends AbstractController
{
    private const INSTALLATION_DATA_SESSION_KEY = 'installation_data';

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly RequestStack $requestStack
    ) {}

    #[Route('/collectInformation', name: 'collect_information')]
    public function index(): Response
    {
        return $this->render('installation/index.html.twig');
    }

    #[Route('/installation', name: 'installation_execute')]
    public function executeInstallation(Request $request): Response
    {
        if (!$request->isMethod('POST')) {
            return $this->redirectToRoute('collect_information');
        }

        if (LockFile::checkLockFileExists()) {
            return $this->redirectToRoute('administration_login');
        }

        $installationData = Installation::createInstallationData($request->getPayload()->getIterator()->getArrayCopy());
        if ($installationData->getValidationResult()->hasViolations()) {
            return $this->render('installation/index.html.twig', [
                'violations' => $installationData->getValidationResult()->getViolations(),
                'controller_name' => 'InstallationController',
            ]);
        }

        $isEnvFileCreated = DotEnvFile::createDonEnvFile($installationData);
        if (!$isEnvFileCreated) {
            return $this->render('installation/index.html.twig', [
                'envFileNotCreated' => $this->translator->trans('installation.errors.envFileNotCreated'),
            ]);
        }

        $this->requestStack->getSession()->set(self::INSTALLATION_DATA_SESSION_KEY, $installationData->toArray());

        return $this->render('installation/installation_progress.html.twig');
    }

    #[Route('/installation/create/database', name: 'installation_create_database', methods: 'post')]
    public function createDatabase(): JsonResponse
    {
        $installationData = $this->getInstallationDataFromSession();

        $connection = PDOConnection::createPDOConnection($installationData);

        $sql = sprintf('CREATE DATABASE IF NOT EXISTS %s;', $installationData->getDatabaseName());

        try {
            $connection->exec($sql);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $this->translator->trans('installation.errors.databaseCreate'),
                'exceptionMessage' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => $this->translator->trans('installation.databaseCreated')]);
    }

    #[Route('/installation/create/tables', name: 'installation_create_tables', methods: 'post')]
    public function createDatabaseTables(KernelInterface $kernel, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput([
                'command' => 'doctrine:migrations:migrate',
                '--no-interaction' => true,
            ]);

            $output = new NullOutput();
            $application->run($input, $output);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $this->translator->trans('installation.errors.tablesCreate'),
                'exceptionMessage' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => $this->translator->trans('installation.tablesCreated')]);
    }

    #[Route('/installation/create/default_data', name: 'installation_create_default_data', methods: 'post')]
    public function createDefaultData(Connection $connection, System $system, Series $series, Stage $stage): JsonResponse
    {
        try {
            $connection->executeQuery($system->getSql());
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $this->translator->trans('installation.errors.defaultsCreate'),
                'exceptionMessage' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $connection->executeQuery($series->getSql());
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $this->translator->trans('installation.errors.defaultsCreate'),
                'exceptionMessage' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $connection->executeQuery($stage->getSql());
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $this->translator->trans('installation.errors.defaultsCreate'),
                'exceptionMessage' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $file = __DIR__ . '/SQL/Default.sql';
        $sql = file_get_contents($file);
        if (!is_string($sql)) {
            return new JsonResponse([
                'message' => $this->translator->trans('installation.errors.defaultsFileNotFound'),
                'exceptionMessage' => "Cannot read $file",
                'trace' => debug_backtrace(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $connection->executeQuery($sql);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $this->translator->trans('installation.errors.defaultsCreate'),
                'exceptionMessage' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => $this->translator->trans('installation.defaultsCreated')]);
    }

    #[Route('/installation/create/user', name: 'installation_create_user', methods: 'post')]
    public function createUser(CreateUserService $createUserService): JsonResponse
    {
        $installationData = $this->getInstallationDataFromSession();

        try {
            $user = $createUserService->createUser($installationData->getUserEmail(), $installationData->getUserPassword());
            $createUserService->saveUser($user);
        } catch (\Exception $exception) {
            if (str_contains($exception->getMessage(), 'Duplicate entry')) {
                return new JsonResponse(['message' => $this->translator->trans('installation.userExists')]);
            }

            return new JsonResponse([
                'message' => $this->translator->trans('installation.errors.userCreate'),
                'exceptionMessage' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => $this->translator->trans('installation.userCreated')]);
    }

    #[Route('/installation/create/translations', name: 'installation_create_translation_files', methods: 'post')]
    public function createTranslationFiles(TranslationService $translationService): JsonResponse
    {
        try {
            $translationService->moveToPublic();
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $this->translator->trans('installation.errors.translationFileCreate'),
                'exceptionMessage' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => $this->translator->trans('installation.translationFileCreated')]);
    }

    #[Route('/installation/create/installation_lock', name: 'installation_create_installation_lock', methods: 'post')]
    public function createInstallationLock(): JsonResponse
    {
        try {
            LockFile::createInstallationLockFile();
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $this->translator->trans('installation.errors.lockFileCreate'),
                'exceptionMessage' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => $this->translator->trans('installation.lockFileCreated')]);
    }

    private function getInstallationDataFromSession(): InstallationData
    {
        $data = $this->requestStack->getSession()->get(self::INSTALLATION_DATA_SESSION_KEY);

        return new InstallationData($data, new ValidationResult(0, []));
    }
}
