<?php

namespace App\Translations;

use App\Defaults;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class TranslationService
{
    private const TRANSLATION_FILE_NAME = 'messages';
    private const TRANSLATION_FILE_EXTENSION = 'yml';
    private const TRANSLATION_DIRECTORY = __DIR__ . '/../../translations';

    private const TARGET_DIRECTORY = __DIR__ . '/../../javascript/src/translations';
    private const TARGET_FILE = 'messages.json';
    private const TARGET_FILE_DEFAULT = 'messages_defaults.json';

    private const SOURCE_FILE_TEMPLATE = '%s/%s.%s.%s';
    private const TARGET_FILE_TEMPLATE = '%s/%s';

    public function __construct(
        #[Autowire('%env(string:LANGUAGE)%')]
        private readonly string $language
    ) {}

    public function moveToPublic(?string $language = null): void
    {
        if ($language === null) {
            $language = $this->language;
        }

        $source = sprintf(
            self::SOURCE_FILE_TEMPLATE,
            realpath(self::TRANSLATION_DIRECTORY),
            self::TRANSLATION_FILE_NAME,
            $language,
            self::TRANSLATION_FILE_EXTENSION
        );

        $targetFile = sprintf(
            self::TARGET_FILE_TEMPLATE,
            realpath(self::TARGET_DIRECTORY),
            self::TARGET_FILE
        );

        $this->create($source, $targetFile);
        $this->createDefault();
    }

    private function createDefault(): void
    {
        if ($this->language === Defaults::LANGUAGE_ENGLISH) {
            return;
        }

        $source = sprintf(
            self::SOURCE_FILE_TEMPLATE,
            realpath(self::TRANSLATION_DIRECTORY),
            self::TRANSLATION_FILE_NAME,
            Defaults::LANGUAGE_ENGLISH,
            self::TRANSLATION_FILE_EXTENSION
        );

        $targetFile = sprintf(
            self::TARGET_FILE_TEMPLATE,
            realpath(self::TARGET_DIRECTORY),
            self::TARGET_FILE_DEFAULT
        );

        $this->create($source, $targetFile);
    }

    private function create(string $source, string $target): void
    {
        $content = \file_get_contents($source);
        try {
            $parsed = \yaml_parse($content);
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not parse translation YML file. Error message: ' . $e->getMessage());
        }

        if (!is_array($parsed)) {
            throw new \RuntimeException('Could not parse translation YML file.');
        }

        $json = \json_encode($parsed);
        if (!is_string($json)) {
            throw new \RuntimeException('Could not json_encode translation data.');
        }

        \file_put_contents($target, $json);
    }
}
