<?php

declare(strict_types=1);

namespace WHInterviewTask\Database;

use Config\Config;
use PDO;
use PDOException;

class DatabaseConnection
{
    public static function getConnection(): PDO
    {
        try {
            $dsn = 'mysql:host=' . Config::get('mysql.host') . ';port=' . Config::get('mysql.port') . ';dbname=' . Config::get('mysql.db');
            return new PDO(
                $dsn,
                Config::get('mysql.user'),
                Config::get('mysql.password'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            throw new PDOException('Failed to connect to the database');
        }
    }
}
