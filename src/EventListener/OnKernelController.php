<?php

namespace App\EventListener;

use App\Static\Installation\LockFile;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsEventListener(event: 'kernel.controller', method: '__invoke')]
class OnKernelController
{
    private const IGNORED_ROUTES = [
        'collect_information',
        'installation_execute',
        'installation_create_database',
        'installation_create_tables',
        'installation_create_default_data',
        'installation_create_user',
        'installation_create_installation_lock',
        'app_install_collect_information',
        'app_install_execute',
        'installation_create_translation_files',
    ];

    public function __construct(
        private readonly UrlGeneratorInterface $router
    ) {
    }

    public function __invoke(ControllerEvent $event): void
    {
        if (LockFile::checkLockFileExists()) {
            return;
        }

        if (in_array($event->getRequest()->get('_route'), self::IGNORED_ROUTES, true)) {
            return;
        }

      $controller = $event->getController();
        if (is_array($controller)) {
            $event->stopPropagation();
            $event->setController(function () {
                return new RedirectResponse($this->router->generate('collect_information'));
            });
        }
    }
}