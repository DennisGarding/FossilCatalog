<?php

namespace App\Counter;

class CookieValues
{
    /** @var array{counterCookie: string, dailyCookie: string} */
    private array $data;

    /**
     * @param array{'counterCookie': string, 'dailyCookie': string} $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getCounterCookieValue(): string
    {
        return $this->data['counterCookie'];
    }

    public function getDailyCookieValue(): string
    {
        return $this->data['dailyCookie'];
    }
}
