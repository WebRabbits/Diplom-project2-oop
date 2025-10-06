<?php

namespace App\Models;
use App\Database\QueryBuilder;
use App\Repositories\Interfaces\UserRepositoriesInterface;
use PDO;

// require_once(__DIR__ . "/../config/dbconnect.php");

class User
{
    private ?int $id = null;
    private string $email;
    private string $password;
    private string $username;
    private QueryBuilder $db;
    private UserRepositoriesInterface $user;


    public function __construct(UserRepositoriesInterface $userRepo, $email = "", $password = "", $username = "")
    {
        $this->email = $email;
        $this->password = $password;
        $this->username = $username;
        // $this->db = $pdo;
        $this->user = $userRepo;
    }

    public function registration($email, $password, $username)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Некорректный Email-адрес";
            return;
        }

        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        $userData = $this->user->getFindEmailByUser($email);
        
        if (isset($userData->email)) {
            echo "Такой пользователь уже существует";
            return;
        }
        
        $registrationOk = $this->user->createUser($email, $hash_password, $username);

        if ($registrationOk) {
            $this->id = $this->user->getUserID();
            $this->email = $email;
            $this->password = $hash_password;
            $this->username = $username;

            echo "Регистрация прошла успешно";
            return true;
        }

        echo "Возникла ошибка при регистрации";
        return false;
    }

    public function login($email, $password)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Некорректный Email-адрес";
            return;
        }

        $userData = $this->user->getFindEmailByUser($email);

        if (!isset($userData->email)) {
            echo "Пользователь не найден";
            return;
        }

        if (!password_verify($password, $userData->password)) {
            echo "Неверный пароль";
            return;
        }

        if (isset($userData)) {
            $this->id = $userData->id;
            $this->email = $userData->email;
            $this->username = $userData->username;

            echo "Авторизация успешна.";
            return true;
        }

        echo "Произошла ошибка при авторизации";
        return false;
    }

    public function logout()
    {
        $this->id = null;
        $this->email = "";
        $this->password = "";
        $this->username = "";
        
        echo "Вы вышли из системы!";
        return true;
    }

    // Геттеры
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    // private function getFindEmail($email) {
    //     return $this->db->getByCondition(table:"users", operator:"=", columns: ["*"], where: ["email" => $email])->getOneResult();
    // }

    // private function createUser($email, $password, $username){
    //     return $this->db->insert(table:"users", columns:[
    //         "email" => $email,
    //         "password" => $password,
    //         "username" => $username
    //     ]);
    // }
}
