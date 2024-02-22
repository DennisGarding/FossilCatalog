<?php

namespace App;

final class Defaults
{
    public const QUERY_LIMIT = 25;

    public const FLASH_TYPE_INFO = 'info';
    public const FLASH_TYPE_SUCCESS = 'success';
    public const FLASH_TYPE_WARNING = 'warning';
    public const FLASH_TYPE_ERROR = 'error';
    public const FLASH_TYPE_DEBUG = 'debug';

    public const LANGUAGE_ENGLISH = 'en';

    public const LATEST_FOSSILS_COUNT = 5;

    private function __construct() {}
}