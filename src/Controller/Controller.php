<?php

declare(strict_types=1);

namespace WHInterviewTask\Controller;

use WHInterviewTask\Database\DatabaseConnection;
use PDO;

class Controller
{
    protected PDO $db;

    /**
     * Controller constructor.
     * Initialize the database connection using dependency injection or directly.
     */
    public function __construct()
    {
        // Initialize the database connection using the DatabaseConnection utility class
        $this->db = DatabaseConnection::getConnection();
    }

    // Now you can use $this->db in your controller methods for database interaction
}
