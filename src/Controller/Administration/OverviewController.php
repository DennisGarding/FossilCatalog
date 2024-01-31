<?php

namespace App\Controller\Administration;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OverviewController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_overview')]
    public function index(): Response
    {
        return $this->render('administration/overview/index.html.twig', [
            'controller_name' => 'OverviewController',
        ]);
    }
}
