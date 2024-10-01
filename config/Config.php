<?php

declare(strict_types=1);

namespace Config;

class Config
{
    /**
     * Retrieves a configuration value based on a dot-separated key.
     */
    public static function get(string $key, $default = null)
    {
        $config = [
            'mysql' => [
                'host' => getenv('MYSQL_HOST'),
                'db' => getenv('MYSQL_DATABASE'),
                'user' => getenv('MYSQL_USER'),
                'password' => getenv('MYSQL_PASSWORD'),
            ],
        ];

        $keys = explode('.', $key);
        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return $default;
            }
            $config = $config[$k];
        }

        return $config;
    }
}
