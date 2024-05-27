<?php

namespace App\Counter;

use App\Repository\VisitorRepository;

class Counter
{
    public function __construct(
        private VisitorRepository $visitorRepository
    ) {}

    public function count(CookieValues $cookieValues): void
    {
        $visitor = $this->visitorRepository->getVisitor($cookieValues->getCounterCookieValue());
        if ($visitor->getShortTermKey() === $cookieValues->getDailyCookieValue()) {
            return;
        }

        $visitor->increment($cookieValues->getDailyCookieValue());
        $this->visitorRepository->save($visitor);
    }
}
