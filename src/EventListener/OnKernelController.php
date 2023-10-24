<?php

namespace App\EventListener;

use App\Installation\LockFile;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsEventListener(event: 'kernel.controller', method: '__invoke')]
class OnKernelController
{
    private const IGNORED_ROUTES = [
        'app_install_collect_information',
        'app_install_execute',
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
                return new RedirectResponse($this->router->generate('app_install_collect_information'));
            });
        }
    }
}