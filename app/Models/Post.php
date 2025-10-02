<?php

namespace App\Models;

use PDO;

require_once(__DIR__ . "/../config/dbconnect.php");

class Post
{
    private $id;
    private $title;
    private $description;
    private $datePublished;
    private $imagePost;
    private $isActive;

    private $db;

    public function __construct($title = null, $description = null, $imagePost = null, $isActive = true)
    {
        $this->title = $title;
        $this->description = $description;
        $this->imagePost = $imagePost;
        $this->isActive = $isActive;
        $this->db = getPDO();
    }

    public function addPost($title, $description, $imagePost)
    {
        $stmt = $this->db->prepare("INSERT INTO `posts` (title, description, image_post) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $imagePost]);

        $this->id = $this->db->lastInsertId();

        if ($stmt->rowCount() > 0) {
            return "Пост успешно добавлен - ID:$this->id";
        } else {
            return "Ошибка. Не удалось добавить пост";
        }
    }

    public function editPost($idPost, $title = "", $description = "", $imagePost = "")
    {
        $stmt = $this->db->prepare("UPDATE `posts` SET `title` = ?, `description` = ?, `image_post` = ? WHERE `id` = ?");
        $stmt->execute([$title, $description, $imagePost, $idPost]);

        if ($stmt->rowCount() > 0) {
            return "Пост успешно изменён";
        } else {
            return "Ошибка. Не удалось обновить запись по посту";
        }
    }
    public function deletePost($id)
    {
        $stmt = $this->db->prepare("DELETE FROM `posts` WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            return "Пост успешно удалён";
        } else {
            return "Ошибка. Не удалось удалить пост";
        }
    }
    public function getAllPosts()
    {
        $stmt = $this->db->prepare("SELECT * FROM `posts`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function uploadImage($imagePost) {}

    public function deleteImage(){}

    public function archive($id, $isActive = 0)
    {
        $stmt = $this->db->prepare("UPDATE `posts` SET `is_active` = ? WHERE `id` = ?");
        $stmt->execute([$isActive, $id]);

        if ($stmt->rowCount() > 0) {
            return "Пост помещён в архив";
        } else {
            return "Ошибка. Не удалось поместить пост в архив";
        }
    }

    public function unarchive($id, $isActive = 1)
    {
        $stmt = $this->db->prepare("UPDATE `posts` SET `is_active` = ? WHERE `id` = ?");
        $stmt->execute([$isActive, $id]);

        if ($stmt->rowCount() > 0) {
            return "Пост активен вновь";
        } else {
            return "Ошибка. Не удалось поместить пост из архив";
        }
    }
}
