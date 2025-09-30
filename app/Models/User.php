<?php 

namespace App\Models;

class User {
    private $email;
    private $password;
    private $username;
    private $avatar;

    public function  __construct($email, $password){
        $this->email = $email;
        $this->password = $password;
    }

    public function registration($email, $password) {

    }

    public function login($email, $password) {

    }

    public function logout(){}

    public function uploadImageAvatar($avatar){}

}

?>