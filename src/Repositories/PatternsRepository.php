<?php
declare(strict_types = 1);

namespace Repositories;

use Database\DatabaseConnection;
use Pattern\Pattern;
use PDO;

class PatternsRepository extends DatabaseConnection
{
    /**
     * @param  string[]
     * @return void
     */
    
    public function importPatternsToDb(array $patterns):void
    {
        $sql = "INSERT INTO `patterns` ( `pattern`) VALUES ( ?)";
        $prepares = $this->connect()->prepare($sql);
        $this->connect()->exec("DELETE  FROM `patterns`");
        foreach ($patterns as $pattern) {
            $prepares->execute([$pattern->getPattern()]);
        }
    }
    
    /**
     * @return Pattern[]
     */
    
    public function getPatternsFromDb():array
    {
        $sql = "SELECT `pattern` FROM `patterns`";
        $patterns = ($this->connect()->query($sql)->fetchAll(PDO::FETCH_CLASS));
        foreach ($patterns as $pattern) {
            $patter[] = new Pattern($pattern->pattern);
        }
        return $patter;
    }
    
    /**
     * @param  string $pattern
     * @return int $id
     */

    public function getPatternId(string $pattern):int
    {
        $sql = "SELECT `id` FROM `patterns` WHERE `pattern` LIKE ?";
        $prepare = $this->connect()->prepare($sql);
        $pattern = '%' . $pattern . '%';
        $prepare->execute([$pattern]);
        $id = $prepare->fetch(PDO::FETCH_COLUMN);
        return intval($id);
    }
}
