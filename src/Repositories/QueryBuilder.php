<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\DatabaseConnection;
use PDO;

class QueryBuilder extends DatabaseConnection
{
    private string $select;
    private string $from;
    private array $where;
    private string $set;
    private string $setValues;
    private string $like;

    public function select(string $select): QueryBuilder
    {
        $this->select = $select;
        return $this;
    }
    public function from(string $from): QueryBuilder
    {
        $this->from = $from;
        return $this;
    }
    public function where(array $where): QueryBuilder
    {
        $this->where = $where;
        return $this;
    }
    public function values(string $values): QueryBuilder
    {
        $this->setValues = $values;
        return $this;
    }

    public function set(string $set): QueryBuilder
    {
        $this->set = $set;
        return $this;
    }

    public function like(string $like): QueryBuilder
    {
        $this->like = $like;
        return $this;
    }
    
    /**
     * "INSERT INTO $this->from ($values) VALUES ($this->setValues)";
     *
     * @param  array $values
     * @return void
     */
    public function insert(array $values): void
    {
        $value = implode(', ', $this->where);
        $sql = "INSERT INTO $this->from ($value) VALUES ($this->setValues)";
        $prepares = $this->connect()->prepare($sql);
        $prepares->execute($values);
    }
    
    /**
     * UPDATE $this->from SET $this->set WHERE $firstWhereValue = $secondWhereValue
     *
     * @param  mixed $values
     * @return void
     */
    public function update(array $values): void
    {
        $firstWhereValue = $this->where[0];
        $secondWhereValue = $this->where[1];
        $sql = "UPDATE $this->from SET $this->set WHERE $firstWhereValue = ?";
        $prepare = $this->connect()->prepare($sql);
        array_push($values, $secondWhereValue);
        $prepare->execute($values);
    }
    
    /**
     *  DELETE FROM $this->from WHERE $firstWhereValue = $secondWhereValue
     *
     * @return void
     */
    public function delete(): void
    {
        if (!empty($this->where)) {
            $firstWhereValue = $this->where[0];
            $secondWhereValue = $this->where[1];
            $sql = "DELETE FROM $this->from WHERE $firstWhereValue = ?";
            $this->connect()->prepare($sql)->execute([$secondWhereValue]);
        }
    }
    
    /**
     * deleteAll (DELETE FROM ($this->from));
     *
     * @return void
     */
    
    public function deleteAll(): void
    {
        $this->connect()->exec("DELETE FROM $this->from");
    }
    
    /**
     *  WHERE $firstWhereValue = $secondWhereValue
     *  SELECT $this->select FROM $this->from or with WHERE statment
     *  SELECT $this->select FROM $this->from WHERE $this->where[0] =  $this->where[0]
     * @return object[]
     */

    public function get(): array
    {
        if (empty($this->where)) {
            $sql = "SELECT $this->select FROM $this->from";
        } else {
            $whereValue = $this->where[0];
            $whereEqualsTo = $this->where[1];
            $sql = "SELECT $this->select FROM $this->from WHERE $whereValue =  '$whereEqualsTo'";
        }

        return $this->connect()->query($sql)->fetchAll(PDO::FETCH_CLASS);
    }
    
    /**
     * SELECT $this->select FROM $this->from WHERE $this->where[0] LIKE $this->like
     * @return string
     */

    public function getLike(): string
    {
        $whereStatment = $this->where[0];
        $this->like = '%' . $this->like . '%';

        $sql = "SELECT $this->select FROM $this->from WHERE $whereStatment LIKE '$this->like'";
        return $this->connect()->query($sql)->fetch(PDO::FETCH_COLUMN);
    }
    
    /**
     * SELECT * FROM $this->from;
     * @return object[]
     */
    
    public function all(): array
    {
        $sql = "SELECT * FROM $this->from";
        return $this->connect()->query($sql)->fetchAll(PDO::FETCH_CLASS);
    }
}
