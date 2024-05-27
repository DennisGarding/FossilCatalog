<?php

namespace App\Update;

use App\Update\Exceptions\DownloadException;
use App\Update\Exceptions\ExtractUpdateException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Updater
{
    private const TAGS_URL = 'https://api.github.com/repos/DennisGarding/FossilCatalog/tags';

    private const UPDATE_FILE = __DIR__ . '/../../update.zip';

    public function __construct(
        private HttpClientInterface $client,
        private readonly RequestStack $requestStack,
        private readonly KernelInterface $kernel,
    ) {}

    public function checkForUpdates(): AvailableUpdate
    {
        $response = $this->client->request('GET', self::TAGS_URL);

        $tags = $response->toArray();
        if (!is_array($tags) || empty($tags) || !isset($tags[0])) {
            throw new \Exception('No tags found');
        }

        $currentVersion = $this->getCurrentVersion();
        $latestTag = new Tag($tags[0]);

        $result = \version_compare($currentVersion, $latestTag->getName(), '<');
        if (!$result) {
            return new AvailableUpdate(false, $currentVersion, $latestTag->getName());
        }

        $session = $this->requestStack->getSession();
        $session->set(UpdateSessionKeys::LAST_VERSION, $latestTag->getName());
        $session->set(UpdateSessionKeys::LAST_VERSION_URL, $latestTag->getZipBallUrl());
        $session->set(UpdateSessionKeys::LAST_VERSION_SHA, $latestTag->getSha());

        return new AvailableUpdate(true, $currentVersion, $latestTag->getName());
    }

    public function downloadUpdate(): void
    {
        try {
            $response = $this->client->request('GET', $this->requestStack->getSession()->get(UpdateSessionKeys::LAST_VERSION_URL));
            \file_put_contents(self::UPDATE_FILE, $response->getContent());
        } catch (\Exception $e) {
            throw new DownloadException($this->requestStack->getSession()->get(UpdateSessionKeys::LAST_VERSION));
        }
    }

    public function extractUpdate(): void
    {
        $zip = new \ZipArchive();
        if ($zip->open(self::UPDATE_FILE) === true) {
            $zip->extractTo(__DIR__ . '/../../');
            $zip->close();

            return;
        }

        throw new ExtractUpdateException($this->requestStack->getSession()->get(UpdateSessionKeys::LAST_VERSION));
    }

    public function executeUpdate(): void
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => true,
        ]);

        $output = new NullOutput();
        $application->run($input, $output);
    }

    public function cleanup(): void
    {
        try {
            \unlink(self::UPDATE_FILE);
        } catch (\Exception $e) {
            throw new \Exception('Could not delete update file');
        }

        try {
            $session = $this->requestStack->getSession();
            $session->remove(UpdateSessionKeys::LAST_VERSION);
            $session->remove(UpdateSessionKeys::LAST_VERSION_URL);
            $session->remove(UpdateSessionKeys::LAST_VERSION_SHA);
        } catch (\Exception $e) {
            throw new \Exception('Could not remove session keys');
        }
    }

    private function getCurrentVersion(): string
    {
        $content = file_get_contents(__DIR__ . '/../../composer.json');
        $content = json_decode($content, true);

        return $content['version'];
    }
}
