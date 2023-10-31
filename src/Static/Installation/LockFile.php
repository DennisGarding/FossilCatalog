<?php

namespace App\Static\Installation;

class LockFile
{
    public const LOCKFILE = __DIR__ . '/../../../install.lock';

    public static function checkLockFileExists(): bool
    {
        if (\file_exists(self::LOCKFILE)) {
            return true;
        }

        return false;
    }

    public static function createInstallationLockFile(): void
    {
        if (!\is_file(self::LOCKFILE)) {
            $dateTime = new \DateTime();
            $content = \sprintf('Installed at: %s', $dateTime->format('Y-m-d H:i:s'));

            \file_put_contents(self::LOCKFILE, $content);
        }

        if (!static::checkLockFileExists()) {
            throw new \RuntimeException('Cannot create installation lock file');
        }
    }
}