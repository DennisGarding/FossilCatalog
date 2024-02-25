<?php

namespace App\DemoData;

use App\DemoData\Handler\CategoryHandler;
use App\DemoData\Handler\FormFieldHandler;
use App\DemoData\Handler\FossilHandler;
use App\DemoData\Handler\HandlerInterface;
use App\DemoData\Handler\TagHandler;

class DemoDataFactory
{
    /**
     * @var array<string, HandlerInterface>
     */
    private array $handlers = [];

    public function __construct(
        private readonly TagHandler       $tagHandler,
        private readonly CategoryHandler  $categoryHandler,
        private readonly FormFieldHandler $formFieldHandler,
        private readonly FossilHandler    $fossilHandler,
    ) {
        $this->handlers = [
            $this->tagHandler->supports() => $this->tagHandler,
            $this->categoryHandler->supports() => $this->categoryHandler,
            $this->formFieldHandler->supports() => $this->formFieldHandler,
            $this->fossilHandler->supports() => $this->fossilHandler,
        ];
    }

    /**
     * @param array<EntityOptions> $options
     */
    public function create(array $options): void
    {
        foreach ($options as $option) {
            $this->handlers[$option->entityClass]->create($option);
        }
    }
}
