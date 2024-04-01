<?php

namespace App\Tests\Unit\Translation;

use App\Translations\TranslationService;
use PHPUnit\Framework\TestCase;

class TranslationServiceTest extends TestCase
{
    public function testMoveToPublic(): void
    {
        $file = __DIR__ . '/../../../javascript/src/translations/messages.json';
        $defaultsFile = __DIR__ . '/../../../javascript/src/translations/messages_defaults.json';
        if (file_exists($file)) {
            unlink($file);
        }

        if (file_exists($defaultsFile)) {
            unlink($defaultsFile);
        }

        static::assertFileDoesNotExist($file);
        static::assertFileDoesNotExist($defaultsFile);

        (new TranslationService('de'))->moveToPublic();

        static::assertFileExists($file);
        static::assertFileExists($defaultsFile);
    }
}
