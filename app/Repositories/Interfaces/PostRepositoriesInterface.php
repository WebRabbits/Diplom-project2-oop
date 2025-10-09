<?php

namespace App\Repositories\Interfaces;

interface PostRepositoriesInterface
{
    public function create(string $title, string $description, string $image);
    public function update(int $id, string $title, string $description, string $image);
    public function delete(int $id);
    public function getAll();
    public function findById(int $id);
    public function makeInactive(int $id);
    public function makeActive(int $id);
    public function uploadImage(array $image);
    public function deleteImage(int $idPost);
    // public function createPostFromData(object $data);
}
