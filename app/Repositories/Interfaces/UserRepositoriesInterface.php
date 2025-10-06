<?php 

namespace App\Repositories\Interfaces;

interface UserRepositoriesInterface{
    public function getFindEmailByUser(string $email);
    public function createUser(string $email, string $password, string $username);

    public function getUserID();
}


?>