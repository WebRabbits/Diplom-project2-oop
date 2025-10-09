<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoriesInterface;
use Aura\SqlQuery\QueryFactory;
use PDO;
use App\Models\User;

class UserRepository implements UserRepositoriesInterface
{
    private PDO $pdo;
    private QueryFactory $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    public function findByEmail(string $email)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from("users")->where("email = :email", ["email" => $email]);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());

        $data = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $data;
        // return $data ? $this->createUserFromData($data) : null; // При добавлении DI контейнера - заменить на эту строку
    }

    public function create(string $email, string $password, string $username)
    {        
        $insert = $this->queryFactory->newInsert();
        $insert->into("users")->cols([
            "email" => $email,
            "password" => $password,
            "username" => $username
        ]);
        $stmt = $this->pdo->prepare($insert->getStatement());
        $stmt->execute($insert->getBindValues());

        return $this->pdo->lastInsertId();      
    }

    // При добавлении DI контейнера - использовать данный метод, чтобы вернуть Объект класса User, а не stdClass
    // public function createUserFromData($data){
    //     return new User(
    //         $data->id,
    //         $data->email,
    //         $data->password,
    //         $data->username
    //     );
    // }
}
