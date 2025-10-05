<?php

namespace App\Database;

use Aura\SqlQuery\QueryFactory;
use Exception;
use PDO;

class QueryBuilder
{
    private $pdo;
    private $queryFactory;
    private $result;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->queryFactory = new QueryFactory("mysql");
    }

    public function getAll(string $table)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from($table);
        $stmt = $this->prepareStatement($select);

        $this->result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $this;
    }

    public function getByCondition(string $table, string $operator, array $columns = ["*"], array $where = [])
    {
        $conditionsKey = key($where);
        $operatorsSet = ["=", ">", "<", ">=", "<=", "!=", "LIKE"];

        try {
            if (in_array($operator, $operatorsSet, true)) {
                $select = $this->queryFactory->newSelect();
                $select->cols($columns)->from($table)->where("$conditionsKey $operator :$conditionsKey", $where);
                $stmt = $this->pdo->prepare($select->getStatement());
                $stmt->execute($select->getBindValues());

                $this->result = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $this;
            } else {
                throw new Exception("Переданное значение оператор недоступно");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function insert(string $table, array $columns)
    {
        $insert = $this->queryFactory->newInsert();
        $insert->into($table)->cols($columns);
        $this->prepareStatement($insert);

        return $this->getLastID();
    }

    public function update(string $table, string $operator, array $bindValues = [], array $where = [])
    {
        $conditionKey = key($where);
        $operatorsSet = $operatorsSet = ["=", ">", "<", ">=", "<=", "!=", "LIKE"];

        try {
            if (!in_array($operator, $operatorsSet)) {
                throw new Exception("Переданное значение оператор недоступно");
            }
            if (empty($bindValues)) {
                throw new Exception("Не заданы поля для изменения и их значения");
            }
            if (empty($where)) {
                throw new Exception("Не задано условие для обновления записи");
            }

            $update = $this->queryFactory->newUpdate();
            $update->table($table)->cols($bindValues)->where("$conditionKey $operator :$conditionKey", $where)->bindValues($bindValues);
            $result = $this->prepareStatement($update);

            return $result->rowCount();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function delete(string $table, int $id)
    {
        $delete = $this->queryFactory->newDelete();
        $delete->from($table)->where("id = :id")->bindValue("id", $id);
        $result = $this->prepareStatement($delete);

        return $result->rowCount();
    }

    // Вернуть массив объекта/объектов результата запроса
    public function result()
    {
        return $this->result;
    }

    public function getOneResult()
    {
        if (isset($this->result) && !empty($this->result())) {
            return $this->result()[0];
        } else {
            return;
        }
    }

    // Вернуть последнее значение ID-записи при изменении записи из БД
    public function getLastID()
    {
        return $this->pdo->lastInsertId();
    }

    public function prepareStatement($typeQuery)
    {
        $stmt = $this->pdo->prepare($typeQuery->getStatement());
        // dd($stmt);
        $stmt->execute($typeQuery->getBindValues());
        return $stmt;
    }
}
