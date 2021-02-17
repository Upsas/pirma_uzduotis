<?php
declare(strict_types=1);
namespace Repositories;

use Database\DatabaseConnection;
use PDO;

class QueryBuilder extends DatabaseConnection
{
    private $select;
    private $from;
    private $where;
    private $set;
    private $test;
    private $like;

    public function select(string $select): object
    {
        $this->select = $select;
        return $this;
    }
    public function from(string $from):object
    {
        $this->from = $from;
        return $this;
    }
    public function where(array $where):object
    {
        $this->where = $where;
        return $this;
    }
    public function values(string $values):object
    {
        $this->test = $values;
        return $this;
    }

    public function set(string $set):object
    {
        $this->set = $set;
        return $this;
    }

    public function like(string $like):object
    {
        $this->like = $like;
        return $this;
    }

    public function insert(array $values):void
    {
        $this->where = implode(', ', $this->where);
         
        $sql = "INSERT INTO $this->from ($this->where) VALUES ($this->test)";
        $prepares = $this->connect()->prepare($sql);
        $prepares->execute($values);
    }

    public function update(array $values):void
    {
        $a = $this->where[0];
        $b = $this->where[1];
        $sql = "UPDATE $this->from SET $this->set WHERE $a = ?";
        $prepare = $this->connect()->prepare($sql);
        array_push($values, $b);
        $prepare->execute($values);
    }

    public function delete():void
    {
        if (!empty($this->where)) {
            $a = $this->where[0];
            $b = $this->where[1];
            $sql = "DELETE FROM $this->from WHERE $a = ?";
            $this->connect()->prepare($sql)->execute([$b]);
        }
    }

    public function deleteAll():void
    {
        $this->connect()->exec("DELETE FROM $this->from");
    }

    public function get():array
    {
        if (empty($this->where)) {
            $sql = "SELECT $this->select FROM $this->from";
        } else {
            ($a = $this->where[0]);
            ($b = $this->where[1]);
            $sql = "SELECT $this->select FROM $this->from WHERE $a =  '$b'";
        }

        return $this->connect()->query($sql)->fetchAll(PDO::FETCH_CLASS);
    }

    public function getLike():object
    {
        $sql = "SELECT $this->select FROM $this->from WHERE $this->where LIKE $this->like";
        $this->like = '%' . $this->like . '%';
        return $this->connect()->prepare($sql)->execute([$this->like])->fetch(PDO::FETCH_COLUMN);
    }

    public function all():array
    {
        $sql = "SELECT * FROM $this->from";
        return $this->connect()->query($sql)->fetchAll(PDO::FETCH_CLASS);
    }
}
