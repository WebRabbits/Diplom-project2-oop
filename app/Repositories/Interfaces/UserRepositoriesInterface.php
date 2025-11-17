<?php 

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Models\ValueObject\Email;

interface UserRepositoriesInterface{
    public function findByEmail(Email $email);
    public function findById(int $id);
    // public function create(string $email, string $password, string $username);
    public function create(User $user);

    public function createUserFromData(object $data);
}


?>