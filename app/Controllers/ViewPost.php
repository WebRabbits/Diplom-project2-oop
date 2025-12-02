<?php 

namespace App\Controllers;

use App\Models\ValueObject\Post\PostId;
use App\Models\ValueObject\UserId;
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

        $post = $this->postRepo->findById(new PostId($idPost));
        $user = $this->userRepo->findById(new UserId($post->getCreatorId()->getValue()));
        
        echo $this->template->render("view", [
            "post" => $post,
            "creatorName" => $user->getUsername()->getValue(),
            "creatorEmail" => $user->getEmail()->getValue()
        ]);
    }
}

?>