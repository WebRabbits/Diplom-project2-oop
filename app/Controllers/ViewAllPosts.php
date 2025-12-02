<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\ValueObject\UserId;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use League\Plates\Engine;

class ViewAllPosts
{
    private PostRepository $postRepo;
    private UserRepository $userRepo;
    private Engine $engine;
    private $template;

    public function __construct(PostRepository $postRepo, UserRepository $userRepo, Engine $engine)
    {
        $this->postRepo = $postRepo;
        $this->userRepo = $userRepo;
        $this->template = $engine;
    }

    public function showAllPosts()
    {
        $posts = $this->postRepo->getAll();
        // dd($_SERVER["DOCUMENT_ROOT"]);
        // dd($posts);
        $creatorData = [];
        // dd($posts);
        foreach ($posts as $post) {
            // dd($post);
            $creatorId = new UserId($post->getCreatorId()->getValue());
            $user = $this->userRepo->findById($creatorId);
            // dd($post->isDraftPost());
            // dd($creatorId);
            // dd($user);
            
            $creatorData[$user->getSecondaryId()->getValue()] = $user->getUsername()->getValue();
            // dd($creatorData);
        }

        echo $this->template->render("posts", [
            "allPosts" => array_filter($posts, function ($post) {
                return $post->isPublishedPost();
            }),
            "creatorData" => $creatorData
        ]);
    }
}
