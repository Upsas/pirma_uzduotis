<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

class DatabaseConnection
{
    private string $host = 'mysql';
    private string $user = 'root';
    private string $password = 'root';
    private string $dbName = 'visma_praktika';

    
    /**
     * @return PDO $pdo
     */

    protected function connect(): PDO
    {
        // Setting dsn
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
        // Making connection to db
        $pdo = new PDO($dsn, $this->user, $this->password);
        // Setting default data type for fetching data
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
}
