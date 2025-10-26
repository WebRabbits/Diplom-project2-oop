<?php

namespace App\Models;

class User
{

    const STATUS_ACTIVATE = "active";
    const STATUS_DISABLED = "disabled";
    private ?int $id = null;
    private string $email;
    private string $password; 
    private string $username;
    private string $status;

    public function __construct(?int $id = null, string $email = "", string $password = "", string $username = "", string $status = "activate")
    {

        if(empty($id)) {
            throw new \InvalidArgumentException("ID cannot be empty");
        }

        if(empty($email)) {
            throw new \InvalidArgumentException("Email cannot be empty");
        }

        if(empty($password)) {
            throw new \InvalidArgumentException("Password cannot be empty");
        }

        if(empty($username)) {
            throw new \InvalidArgumentException("Username cannot be empty");
        }

        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->username = $username;
        $this->status = self::STATUS_ACTIVATE;
    }

    public static function createUser(int $id, string $email, string $password, string $username): User {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $status = self::STATUS_ACTIVATE;

        return new self($id, $email, $password, $username, $status);
    }

    public function changeEmail(string $newEmail): void{
        if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email is incorrect");
        }

        $this->email = $newEmail;
    }

    public function changePassword(string $currentPassword, string $newPassword, string $repeatPassword): void {
        if(empty($currentPassword) || empty($newPassword) || empty($repeatPassword)) {
            throw new \InvalidArgumentException("Fields cannot be empty");
        }

        if(!password_verify($currentPassword, $this->getPassword())) {
            throw new \InvalidArgumentException("Current password is incorrect");
        }

        if (password_verify($newPassword, $this->getPassword())) {
            throw new \InvalidArgumentException("New password cannot be same as current password");
        }

        if($newPassword !== $repeatPassword) {
            throw new \InvalidArgumentException("Password values do not match");
        }

        if(strlen($newPassword) < 8 || strlen($repeatPassword) < 8) {
            throw new \InvalidArgumentException("New password and Repeat password must be at least 8 characters long");
        }

        $this->password = $this->passwordHash($newPassword);
    }

    public function changeUsername(string $username): void {
        if(strlen($username) < 2) {
            throw new \InvalidArgumentException("USername must be at least 2 characters long");
        }

        $this->username = trim($username);
    }

    public function passwordHash($password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function passwordVerify($password): bool {
        return password_verify($password, $this->getPassword());
    }

    public function activate(): void{
        if($this->isActivate()) {
            throw new \InvalidArgumentException("User cannot be activated because the user is already active");
        }

        $this->status = self::STATUS_ACTIVATE;
    }

    public function disable() {
        if($this->isDisabled()) {
            throw new \InvalidArgumentException("User cannot be disabled because the user is already disable");
        }

        $this->status = self::STATUS_DISABLED;
    }

    public function isActivate(): string {
        return $this->status = self::STATUS_ACTIVATE;
    }

    public function isDisabled(): string {
        return $this->status = self::STATUS_DISABLED;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string{
        return $this->password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getStatus(): string{
        return $this->status;
    }

    // public function registration($email, $password, $username)
    // {
    //     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //         echo "Некорректный Email-адрес";
    //         return;
    //     }

    //     $hash_password = password_hash($password, PASSWORD_DEFAULT);

    //     $userData = $this->user->findByEmail($email);

    //     if (isset($userData->email)) {
    //         echo "Такой пользователь уже существует";
    //         return;
    //     }
        
        
    //     $lastInsertID = $this->user->create($email, $hash_password, $username);

    //     if ($lastInsertID) {
    //         $this->id = $lastInsertID;
    //         $this->email = $email;
    //         $this->password = $hash_password;
    //         $this->username = $username;

    //         // echo "Регистрация прошла успешно";
    //         // return true;
    //     }

    //     echo "Возникла ошибка при регистрации";
    //     return false;
    // }

    // public function login($email, $password)
    // {
    //     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //         echo "Некорректный Email-адрес";
    //         return;
    //     }

    //     $userData = $this->user->findByEmail($email);

    //     if (!isset($userData->email)) {
    //         echo "Пользователь не найден";
    //         return;
    //     }

    //     if (!password_verify($password, $userData->password)) {
    //         echo "Неверный пароль";
    //         return;
    //     }

    //     if (isset($userData)) {
    //         $this->id = $userData->id;
    //         $this->email = $userData->email;
    //         $this->username = $userData->username;

    //         echo "Авторизация успешна.";
    //         return true;
    //     }

    //     echo "Произошла ошибка при авторизации";
    //     return false;
    // }

    // public function logout()
    // {
    //     $this->id = null;
    //     $this->email = "";
    //     $this->password = "";
    //     $this->username = "";
        
    //     echo "Вы вышли из системы!";
    //     return true;
    // }

    // Геттеры

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
