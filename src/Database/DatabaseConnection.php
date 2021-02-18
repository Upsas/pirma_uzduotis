<?php
declare(strict_types = 1);

namespace App\Database;

use PDO;

class DatabaseConnection
{
    private string $host = '127.0.0.1';
    private string $user = 'root';
    private string $password = '';
    private string $dbName = 'visma_praktika';
    
    /**
     * @return object
     */

    protected function connect():object
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
