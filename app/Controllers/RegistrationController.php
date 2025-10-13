<?php 

namespace App\Controllers;

use App\Repositories\UserRepository;
class RegistrationController{
    private $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function showRegistration() {
        include(__DIR__ . "/../Views/registration.php");
    }
}

?>