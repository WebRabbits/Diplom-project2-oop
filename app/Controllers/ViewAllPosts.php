<?php 

namespace App\Controllers;

use App\Repositories\PostRepository;

class ViewAllPosts{
    private PostRepository $postRepo;
    private $posts;

    public function __construct(PostRepository $postRepo){
        $this->postRepo = $postRepo;
    }

    public function showAllPosts(){
        $posts = $this->postRepo->getAll();
        include(__DIR__ . "/../Views/posts.php");
        return;
    }
}

?>