<?php 

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Models\ValueObject\Email;
use App\Models\ValueObject\UserId;

interface UserRepositoriesInterface{
    public function findByEmail(Email $email);
    public function findById(UserId $id);
    // public function create(string $email, string $password, string $username);
    public function create(User $user);

    public function createUserFromData(object $data);
}


?>