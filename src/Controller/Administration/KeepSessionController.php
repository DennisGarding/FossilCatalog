<?php

namespace App\Controller\Administration;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class KeepSessionController extends AbstractController
{
    #[Route('/admin/keep', name: 'app_admin_keep_session')]
    public function categoryIndex(): Response
    {
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
