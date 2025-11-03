<?php 

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use League\Plates\Engine;

class ViewPost{
    private UserRepository $userRepo;
    private PostRepository $postRepo;
    private $template;

    public function __construct(UserRepository $userRepo, PostRepository $postRepo, Engine $engine) {
        $this->userRepo = $userRepo;
        $this->postRepo = $postRepo;
        $this->template = $engine;
    }

    public function viewPost(array $var) {
        $idPost = $var["id"];

        $post = $this->postRepo->findById($idPost);
        $user = $this->userRepo->findById($post->getIdCreator());
        
        echo $this->template->render("view", [
            "post" => $post,
            "creatorName" => $user->getUsername(),
            "creatorEmail" => $user->getEmail()
        ]);
    }
}

?>