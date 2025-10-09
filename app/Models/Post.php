<?php

namespace App\Models;

use App\Repositories\Interfaces\PostRepositoriesInterface;
use PDO;

class Post
{
    private ?int $id = null;
    private string $title;
    private string $description;
    private string $datePublished;
    private string $imagePost;
    private int $isActive;
    private PostRepositoriesInterface $post;
    private $result = null;

    public function __construct(PostRepositoriesInterface $postRepo, string $title = "", string $description = "", string $date = "", string $imagePost = "", bool $isActive = true)
    {
        $this->title = $title;
        $this->description = $description;
        $this->datePublished = $date;
        $this->imagePost = $imagePost;
        $this->isActive = $isActive;
        // $this->db = $pdo;
        $this->post = $postRepo;
    }

    public function addPost(string $title, string $description, array $imagePost)
    {
        if (empty($title) || empty($description)) {
            echo "Поле не может быть пустым";
            return;
        }

        if (!isset($imagePost)) {
            echo "Выберите картинку!";
            return;
        }

        $imagePost = $this->post->uploadImage($imagePost);
        // dd($imagePost);

        if (is_null($imagePost)) return;

        $lastInsertID = $this->post->create($title, $description, $imagePost);

        if ($lastInsertID) {
            $this->id = $lastInsertID;
            $newPost = $this->post->findById($this->id);
            $this->id = $newPost->id;
            $this->title = $newPost->title;
            $this->description = $newPost->description;
            $this->datePublished = $newPost->date;
            $this->imagePost = $newPost->image_post;
            $this->isActive = $newPost->is_active;

            echo "Пост успешно добавлен";
            return true;
        }

        echo "Ошибка. Не удалось добавить пост";
        return false;
    }
    public function editPost(int $id, string $title = "", string $description = "", array $imagePost = [])
    {
        $currentPost = $this->post->findById($id);
        
        
        if (!$currentPost) {
            return;
        }

        if (empty($title) && empty($description) && empty($imagePost)) {
            echo "Все поля не могут быть пустыми. Измените значение хотя бы в одном поле!";
            return;
        }

        if (!empty($imagePost)) {
            $imagePost = $this->post->uploadImage($imagePost);
            $this->post->deleteImage($id);
        } else {
            $imagePost = $currentPost->image_post;
        }

        $result = $this->post->update($id, $title, $description, $imagePost);

        if ($result > 0) {
            echo "Пост успешно изменён";
            return true;
        }

        echo "Ошибка. Не удалось обновить запись по посту";
        return false;
    }
    public function deletePost(int $id)
    {
        if (!$this->post->findById($id)) {
            return;
        }
        
        $this->post->deleteImage($id);
        $result = $this->post->delete($id);
        // die;
        if ($result > 0) {
            echo "Пост успешно удалён";
            return true;
        }

        echo "Ошибка. Не удалось удалить пост";
        return false;
    }
    public function getAllPosts()
    {
        return $this->post->getAll();
    }

    public function getPostById(int $id)
    {
        $this->result = $this->post->findById($id);

        if (!$this->result()) {
            echo "Данный пост не существует";
            return;
        }

        return $this->result();
    }
    
    public function archive(int $id)
    {
        if (!$this->post->findById($id)) {
            return;
        }

        $result = $this->post->makeInactive($id);

        if ($result > 0) {
            echo "Пост помещён в архив";
            return true;
        }

        echo "Ошибка. Не удалось поместить пост в архив";
        return false;
    }

    public function unarchive(int $id)
    {
        if (!$this->post->findById($id)) {
            return;
        }

        $result = $this->post->makeActive($id);

        if ($result > 0) {
            echo "Пост активен вновь";
            return true;
        }

        echo "Ошибка. Не удалось переместить пост из архив";
        return false;
    }

    //// Геттеры

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDatePublished()
    {
        return $this->datePublished;
    }

    public function getImagePost()
    {
        return $this->imagePost;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function result()
    {
        return $this->result;
    }
}
