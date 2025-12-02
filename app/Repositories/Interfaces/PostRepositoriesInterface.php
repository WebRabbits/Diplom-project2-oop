<?php

namespace App\Repositories\Interfaces;

use App\Models\Post;
use App\Models\ValueObject\Post\CreatorId;
use App\Models\ValueObject\Post\PostId;
use App\Models\ValueObject\Post\Status;
use DateTime;

interface PostRepositoriesInterface
{
    public function create(Post $post);
    // int $idCreator, string $title, string $description, array $image
    public function update(Post $post);
    public function delete(Post $post);
    public function getAll();
    public function findById(PostId $id);
    public function findPostsByCreator(CreatorId $creatorId);
    public function makePublished(Status $status, PostId $id, DateTime $publishedTime);
    public function makeArchived(Status $status, PostId $id);
    // public function uploadImage(array $image);
    // public function deleteImage(PostId $id);
    public function createPostFromData($data);
}
