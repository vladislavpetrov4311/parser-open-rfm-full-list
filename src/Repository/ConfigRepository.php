<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Repository;

class ConfigRepository
{
    private static array $config = [];

    /**
     * @param $key
     * @return mixed
     */
    public static function get($key): mixed
    {
        if (!self::$config) {
            self::$config = require_once __DIR__ . '/../config/config.php';
        }

        return self::$config[$key];
    }
}