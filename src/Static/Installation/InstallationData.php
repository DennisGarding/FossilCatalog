<?php

namespace App\Static\Installation;

use App\Static\Validation\ValidationResult;
use App\Static\Validation\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class InstallationData
{
    private string $databaseName;

    private string $databaseUsername;

    private string $databasePassword;

    private string $databaseHost;

    private string $databasePort;

    private string $userEmail;

    private string $userPassword;

    private string $userPasswordConfirm;

    private string $appSecret;

    private string $language;

    private ValidationResult $validationResult;

    /**
     * @param array<string, string> $data
     */
    public function __construct(array $data, ValidationResult $validationResult)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        $this->validationResult = $validationResult;
    }

    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    public function getDatabaseUsername(): string
    {
        return $this->databaseUsername;
    }

    public function getDatabasePassword(): string
    {
        return $this->databasePassword;
    }

    public function getDatabaseHost(): string
    {
        return $this->databaseHost;
    }

    public function getDatabasePort(): string
    {
        return $this->databasePort;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getUserPassword(): string
    {
        return $this->userPassword;
    }

    public function getUserPasswordConfirm(): string
    {
        return $this->userPasswordConfirm;
    }

    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    public function getValidationResult(): ValidationResult
    {
        return $this->validationResult;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return array<string, array<int, Constraint>>
     */
    public static function getValidation(): array
    {
        return [
            'databaseName' => [new NotBlank(), new Type(Validator::TYPE_STRING)],
            'databaseUsername' => [new NotBlank(), new Type(Validator::TYPE_STRING)],
            'databasePassword' => [new NotBlank(), new Type(Validator::TYPE_STRING)],
            'databaseHost' => [new NotBlank(), new Type(Validator::TYPE_STRING)],
            'databasePort' => [new NotBlank(), new Type(Validator::TYPE_STRING), new Length(['min' => 4])],
            'userEmail' => [new NotBlank(), new Type(Validator::TYPE_STRING)],
            'userPassword' => [new NotBlank(), new Type(Validator::TYPE_STRING)],
            'userPasswordConfirm' => [new NotBlank(), new Type(Validator::TYPE_STRING)],
            'appSecret' => [new NotBlank(), new Type(Validator::TYPE_STRING), new Length(['min' => 32])],
            'language' => [new NotBlank(), new Choice(['choices' => ['en', 'de']])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'databaseName' => $this->databaseName,
            'databaseUsername' => $this->databaseUsername,
            'databasePassword' => $this->databasePassword,
            'databaseHost' => $this->databaseHost,
            'databasePort' => $this->databasePort,
            'userEmail' => $this->userEmail,
            'userPassword' => $this->userPassword,
            'userPasswordConfirm' => $this->userPasswordConfirm,
            'appSecret' => $this->appSecret,
            'language' => $this->language,
        ];
    }
}
