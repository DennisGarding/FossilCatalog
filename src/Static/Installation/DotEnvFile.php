<?php

namespace App\Static\Installation;

class DotEnvFile
{
    const DOT_ENV = __DIR__ . '/../../../.env';

    const DOT_ENV_DIST = __DIR__ . '/../../../.env.dist';

    const DATABASE_REPLACE = '___DATABASE_STRING___';

    const APP_SECRET_REPLACE = '___APP_SECRET___';


    public static function createDonEnvFile(InstallationData $installationData): bool
    {
        $dotEnvContent = \file_get_contents(self::DOT_ENV_DIST);
        if (!is_string($dotEnvContent)) {
            throw new \UnexpectedValueException(sprintf('Cannot read content of %s', self::DOT_ENV_DIST));
        }

        // mysql://root:root@mysql:3306/fossils
        $databaseConnectionString = sprintf(
            'mysql://%s:%s@%s:%s/%s',
            $installationData->getDatabaseUsername(),
            $installationData->getDatabasePassword(),
            $installationData->getDatabaseHost(),
            $installationData->getDatabasePort(),
            $installationData->getDatabaseName()
        );

        $dotEnvContent = \str_replace(self::DATABASE_REPLACE, $databaseConnectionString, $dotEnvContent);
        $dotEnvContent = \str_replace(self::APP_SECRET_REPLACE, $installationData->getAppSecret(), $dotEnvContent);

        \file_put_contents(self::DOT_ENV, $dotEnvContent);

        return \file_exists(self::DOT_ENV);
    }
}