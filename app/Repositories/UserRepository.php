<?php

namespace App\Repositories;

use App\Models\ValueObject\Email;
use App\Models\ValueObject\Password;
use App\Models\ValueObject\UserId;
use App\Models\ValueObject\Username;
use App\Models\ValueObject\UserStatus;
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

    public function findByEmail(Email $email): bool|User
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from("users")->where("email = :email", ["email" => $email->getValue()]);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());

        $data = $stmt->fetch(PDO::FETCH_OBJ);
        dd($data);
        // return $data;
        return $data ? $this->createUserFromData($data) : false; // При добавлении DI контейнера - заменить на эту строку
    }

    public function findById(int $id){
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from("users")->where("id = :id", ["id" => $id]);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());

        $data = $stmt->fetch(PDO::FETCH_OBJ);

        return $data ? $this->createUserFromData($data) : false;
    }

    public function create(User $user): bool|User
    {   
        $insert = $this->queryFactory->newInsert();
        $insert->into("users")->cols([
            "secondary_id" => $user->getSecondaryId()->getValue(),
            "email" => $user->getEmail()->getValue(),
            "password" => $user->getPassword()->getHash(),
            "username" => $user->getUsername()->getValue(),
            "status" => $user->getStatus()->getValue()
        ]);
        $stmt = $this->pdo->prepare($insert->getStatement());
        $stmt->execute($insert->getBindValues());

        $userId =  $this->pdo->lastInsertId();
        
        return $userId ? User::createUser($userId, $user->getSecondaryId(), $user->getEmail(), $user->getPassword(), $user->getUsername()) : false;
    }

    // При добавлении DI контейнера - использовать данный метод, чтобы вернуть Объект класса User, а не stdClass
    public function createUserFromData($data): User{
        $secondaryId = new UserId($data->secondary_id);
        $email = new Email($data->email);
        $password = new Password($data->password);
        $username = new Username($data->username);
        $status = new UserStatus($data->status);

        return new User(
            (int) $data->id,
            $secondaryId,
            $email,
            $password,
            $username,
            $status
        );
    }
}
