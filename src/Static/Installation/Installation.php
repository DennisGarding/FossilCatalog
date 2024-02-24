<?php

namespace App\Static\Installation;

use App\Resolver\IntResolver;
use App\Static\Validation\Validator;

class Installation
{
    /**
     * @param array<string, string> $data
     */
    public static function createInstallationData(array $data): InstallationData
    {
        $data[AppSecret::KEY] = AppSecret::create();

        $validationResult = Validator::validate($data, InstallationData::getValidation());

        return new InstallationData($data, $validationResult);
    }
}