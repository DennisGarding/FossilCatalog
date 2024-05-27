<?php

namespace App\Controller\Gallery;

use App\Counter\CookieValues;
use App\Counter\Counter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CounterController extends AbstractController
{
    public function __construct(
        private readonly Counter $counter
    ) {}

    #[Route('/count', name: 'gallery_count')]
    public function count(Request $request): JsonResponse
    {
        $cookieValues = new CookieValues($request->query->all());
        $this->counter->count($cookieValues);

        return new JsonResponse();
    }
}
