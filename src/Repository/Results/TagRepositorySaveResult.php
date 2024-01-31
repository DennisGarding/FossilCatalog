<?php

namespace App\Repository\Results;

use App\Entity\Tag;
use App\Static\Validation\ValidationResult;

class TagRepositorySaveResult
{
    public function __construct(
        private readonly Tag $tag,
        private readonly ValidationResult $isSuccessful
    ) {}

    public function getTag(): Tag
    {
        return $this->tag;
    }

    public function getIsSuccessful(): ValidationResult
    {
        return $this->isSuccessful;
    }
}
