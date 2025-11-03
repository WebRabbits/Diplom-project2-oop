<?php 

namespace App\Controllers;

use App\Models\User;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use League\Plates\Engine;

class ViewAllPosts{
    private PostRepository $postRepo;
    private UserRepository $userRepo;
    private Engine $engine;
    private $template;

    public function __construct(PostRepository $postRepo, UserRepository $userRepo, Engine $engine){
        $this->postRepo = $postRepo;
        $this->userRepo = $userRepo;
        $this->template = $engine;
    }

    public function showAllPosts(){
        $posts = $this->postRepo->getAll();

        $creatorData = [];
        foreach($posts as $post) {
            $user = $this->userRepo->findById($post->getIdCreator());
            $creatorData[$user->getId()] = $user->getUsername();
        }
        echo $this->template->render("posts", [
            "allPosts" => $posts,
            "creatorData" => $creatorData
        ]);
    }
}

?>