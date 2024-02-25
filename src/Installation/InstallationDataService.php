<?php

namespace App\Installation;

use App\Static\Installation\Installation;
use App\Static\Installation\InstallationData;

class InstallationDataService
{
    private ?InstallationData $installationData = null;

    public function getInstallationData(): InstallationData
    {
        if ($this->installationData instanceof InstallationData) {
            return $this->installationData;
        }

        $this->installationData = Installation::createInstallationData([
            'databaseName' => getenv('DB_DATABASE') ?: 'fossilCatalog',
            'databaseUsername' => getenv('DB_USER') ?: 'root',
            'databasePassword' => getenv('DB_PASSWORD') ?: 'root',
            'databaseHost' => getenv('DB_HOST') ?: 'mysql',
            'databasePort' => getenv('DB_PORT') ?: '3306',
            'userEmail' => 'test@example.com',
            'userPassword' => 'test1234',
            'userPasswordConfirm' => 'test1234',
            'language' => 'de',
        ]);

        return $this->installationData;
    }
}
