<?php

namespace App\Repositories\Interfaces;

interface PostRepositoriesInterface
{
    public function create(int $idCreator, string $title, string $description, array $image);
    public function update(int $id, int $idCreator, string $title, string $description, array $image);
    public function delete(int $id);
    public function getAll();
    public function findById(int $id);
    public function makeInactive(int $id);
    public function makeActive(int $id);
    public function uploadImage(array $image);
    public function deleteImage(int $idPost);
    public function createPostFromData(object $data);
}
