<?php 

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use League\Plates\Engine;

class ProfileController{
    private UserRepository $userRepo;
    private PostRepository $postRepo;
    private Engine $engine;
    private $template;

    public function __construct(UserRepository $userRepo, PostRepository $postRepo, Engine $engine){
        $this->userRepo = $userRepo;
        $this->postRepo = $postRepo;
        $this->template = $engine;
    }

    public function showProfile(array $vars){
        $idUser = $vars["id"];
        // dd($idUser);
        $user = $this->userRepo->findById($idUser);
        // dd($user);
        $allPostThisUser = $this->postRepo->findPostsByCreator($idUser);
        // dd($allPostThisUser);

        echo $this->template->render("profile", [
            "posts" => $allPostThisUser,
            "user" => $user
        ]);
        // include(__DIR__ . "/../Views/profile.php");
    }

}

?>