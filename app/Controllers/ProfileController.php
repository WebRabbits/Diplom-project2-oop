<?php 

namespace App\Controllers;

use App\Repositories\UserRepository;

class ProfileController{
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo){
        $this->userRepo = $userRepo;
    }

    public function showProfile(){
        include(__DIR__ . "/../Views/profile.php");
    }
}

?>