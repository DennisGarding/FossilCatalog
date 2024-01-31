<?php

namespace App\Translations;

use App\Defaults;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use function yaml_parse_file;

class TranslationService
{
    private const TRANSLATION_FILE_NAME = 'messages';
    private const TRANSLATION_FILE_EXTENSION = 'yml';
    private const TRANSLATION_DIRECTORY = __DIR__ . '/../../translations';

    private const TARGET_DIRECTORY = __DIR__ . '/../../public/translations';
    private const TARGET_FILE = 'messages.json';
    private const TARGET_FILE_DEFAULT = 'messages_defaults.json';

    private const SOURCE_FILE_TEMPLATE = '%s/%s.%s.%s';
    private const TARGET_FILE_TEMPLATE = '%s/%s';

    public function __construct(
        #[Autowire('%env(string:LANGUAGE)%')]
        private readonly string $language
    ) {}

    public function moveToPublic(): void
    {
        $source = sprintf(
            self::SOURCE_FILE_TEMPLATE,
            realpath(self::TRANSLATION_DIRECTORY),
            self::TRANSLATION_FILE_NAME,
            $this->language,
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

    /**
     * @return array<string, array<string, string>>
     */
    public function getTranslations(): array
    {
        $translationFile = sprintf(
            self::TARGET_FILE_TEMPLATE,
            realpath(self::TARGET_DIRECTORY),
            self::TARGET_FILE
        );

        if (!file_exists($translationFile)) {
            throw new \RuntimeException('Translation file does not exist.');
        }

        $defaultTranslationFile = sprintf(
            self::TARGET_FILE_TEMPLATE,
            realpath(self::TARGET_DIRECTORY),
            self::TARGET_FILE_DEFAULT
        );

        if (!file_exists($defaultTranslationFile)) {
            $defaultTranslationFile = $translationFile;
        }

        return [
            'translations' => json_decode(file_get_contents($translationFile), true),
            'defaultTranslations' => json_decode(file_get_contents($defaultTranslationFile), true),
        ];
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
        $parsed = yaml_parse_file($source);
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
