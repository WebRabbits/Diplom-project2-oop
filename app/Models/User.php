<?php 

namespace App\Models;
use PDO;
require_once(__DIR__ . "/../config/dbconnect.php");

class User {
    private $id;
    private $email;
    private $password;
    private $username;
    private $db;

    public function __construct($email = "", $password = "", $username = ""){
        $this->email = $email;
        $this->password = $password;
        $this->username = $username;
        $this->db = getPDO();
    }

    public function registration($email, $password, $username) {

        $stmt = $this->db->prepare("SELECT `email` FROM `users` WHERE email = ?");
        $stmt->execute([$email]);
        $userData = $stmt->fetch(PDO::FETCH_OBJ);

        if(isset($userData->email)) {
            return "Такой пользователь уже существует";
        }

        $stmt = $this->db->prepare("INSERT INTO `users` (email, password, username) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $username]);
        return;
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE email = ?");
        $stmt->execute([$email]);
        $userData = $stmt->fetch(PDO::FETCH_OBJ);

        if(!isset($userData->email)) {
            echo "Пользователь не найден";
            return;
        }

        if($password !== $userData->password) {
            echo "Неверный пароль";
            return;
        }

        $this->id = $userData->id;
        $this->email = $userData->email;
        $this->username = $userData->username;

        return "Авторизация успешна. Привет $this->username";
    }

    public function logout(){
        return "Вы вышли из системы!";
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getUsername() {
        return $this->username;
    }

    

}

?>