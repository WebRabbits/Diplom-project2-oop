<?php 

namespace App\Repositories\Interfaces;

interface UserRepositoriesInterface{
    public function findByEmail(string $email);
    public function findById(int $id);
    public function create(string $email, string $password, string $username);

    public function createUserFromData(object $data);
}


?>