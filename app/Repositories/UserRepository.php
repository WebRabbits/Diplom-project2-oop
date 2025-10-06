<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoriesInterface;
use App\Database\QueryBuilder;

class UserRepository implements UserRepositoriesInterface
{
    private $db;

    public function __construct(QueryBuilder $pdo)
    {
        $this->db = $pdo;
    }

    public function getFindEmailByUser(string $email)
    {
        return $this->db->getByCondition(table: "users", operator: "=", columns: ["*"], where: ["email" => $email])->getOneResult();
    }

    public function createUser(string $email, string $password, string $username)
    {
        return $this->db->insert(table: "users", columns: [
            "email" => $email,
            "password" => $password,
            "username" => $username
        ]);
    }

    public function getUserID(){
        return $this->db->getLastID();
    }
}
