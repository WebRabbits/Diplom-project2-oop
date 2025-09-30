<?php

namespace App\Models;

class Post
{
    private $title;
    private $description;
    private $datePublished;
    private $imagePost;
    private $isActive;

    public function __construct($title, $description, $imagePost, $isActive = true)
    {
        $this->title = $title;
        $this->description = $description;
        $this->imagePost = $imagePost;
        $this->isActive = $isActive;
    }

    public function addPost($title, $description, $imagePost) {}

    public function editPost($title = "", $description = "", $imagePost = null) {}
    public function deletePost(){}
    public function uploadImage($imagePost) {}

    public function archive() {}

    public function unarchive() {}

}
