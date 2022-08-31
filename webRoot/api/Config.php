<?php

/**
 * Provides access to the config parameters.
 */
class Config
{

    private static ?array $config = null;

    /**
     * Gets an array with the config's contents.
     * @return array
     */
    public static function getConfig(): array
    {
        if (self::$config === null) {
            self::$config = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/config.json"), true);
        }
        return self::$config;
    }
}