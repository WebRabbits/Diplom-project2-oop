<?php

namespace App\Models;

use DateTime;
use PDO;

require_once(__DIR__ . "/../config/dbconnect.php");

class Post
{
    private ?int $id = null;
    private string $title;
    private string $description;
    private string $datePublished;
    private array $imagePost;
    private int $isActive;

    private $db;
    private $result = null;

    public function __construct(string $title = "", string $description = "", array $imagePost = [], bool $isActive = true)
    {
        $this->title = $title;
        $this->description = $description;
        $this->imagePost = $imagePost;
        $this->isActive = $isActive;
        $this->db = getPDO();
    }

    public function addPost(string $title, string $description, string $imagePost)
    {
        if (empty($title) || empty($description)) {
            echo "Поле не может быть пустым";
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO `posts` (title, description, image_post) VALUES (?, ?, ?)");
        $ok = $stmt->execute([$title, $description, $imagePost]);

        $this->id = $this->db->lastInsertId();

        if ($ok) {
            $this->id = $this->db->lastInsertId();
            $newPost = $this->getPostById($this->id);
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

    public function editPost(int $id, string $title = "", string $description = "", string $imagePost = "")
    {
        if (!$this->getPostById($id)) {
            echo "Данный пост не существует!";
            return;
        }

        $stmt = $this->db->prepare("UPDATE `posts` SET `title` = ?, `description` = ?, `image_post` = ? WHERE `id` = ?");
        $stmt->execute([$title, $description, $imagePost, $id]);

        if ($stmt->rowCount() > 0) {
            echo "Пост успешно изменён";
            return true;
        }

        echo "Ошибка. Не удалось обновить запись по посту";
        return false;
    }
    public function deletePost(int $id)
    {
        if (!$this->getPostById($id)) {
            echo "Данный пост не существует!";
            return;
        }

        $stmt = $this->db->prepare("DELETE FROM `posts` WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo "Пост успешно удалён";
            return true;
        }

        echo "Ошибка. Не удалось удалить пост";
        return false;
    }
    public function getAllPosts()
    {
        $stmt = $this->db->prepare("SELECT * FROM `posts`");
        $stmt->execute();
        $this->result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $this;
    }

    public function getPostById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM `posts` WHERE id = ?");
        $stmt->execute([$id]);
        $this->result = $stmt->fetch(PDO::FETCH_OBJ);
        return $this->result;
    }
    public function uploadImage(array $image, ?int $idPost = null)
    {
        $arrayMIMEType = ["image/png", "image/jpeg"];
        $nameFile = pathinfo($image["name"], PATHINFO_FILENAME);
        $extensionFile = "." . pathinfo($image["name"], PATHINFO_EXTENSION);
        $typeFile = $image["type"];
        $tmpName = $image["tmp_name"];
        $targetDirectory = dirname($_SERVER["DOCUMENT_ROOT"]) . "/img/posts/";
        $targetFile = $targetDirectory . $nameFile . "_" . uniqid() . "_" . time() . $extensionFile;
        $relativePathImage = "/img/posts/" . basename($targetFile);

        if (!in_array($typeFile, $arrayMIMEType)) {
            echo "Недоступный формат файла для загрузки";
            return;
        }

        if (is_uploaded_file($tmpName)) {
            if (!move_uploaded_file($tmpName, $targetFile)) {
                echo "Произошла ошибка при загрузке файла на сервер";
                return;
            }
            $stmt = $this->db->prepare("UPDATE `posts` SET `image_post` = ? WHERE id = ?");
            $stmt->execute([$relativePathImage, $idPost]);
        }

        if ($stmt->rowCount() > 0) {
            echo "Картинка загружена успешно";
            return;
        } else {
            unlink($targetFile);
            echo "Непредвиденная ошибка при загрузке картинки";
            return false;
        }
    }

    public function deleteImage(int $id)
    {
        
        $stmt = $this->db->prepare("SELECT `image_post` FROM `posts` WHERE id = ?");
        $stmt->execute([$id]);
        $this->result = $stmt->fetch(PDO::FETCH_OBJ);

        $targetDirectory = dirname($_SERVER["DOCUMENT_ROOT"]);
        $targetFile = $this->result()->image_post;
        dd($targetDirectory);
        dd($targetFile);

        $file = $targetDirectory . $targetFile;

        if(isset($this->result)) {
            if(file_exists($file)) {
                unlink($file);
            }

            if(!file_exists($file)){
                $stmt = $this->db->prepare("UPDATE `posts` SET `image_post` = ? WHERE id = ?");
                $stmt->execute(["", $id]);
            }

            if($stmt->rowCount() > 0) {
                echo "Файл успешно удалён с сервера и БД";
                return true;
            }
        }

        echo "Произошла непредвиденная ошибка при удалении файла";
        return false;
    }

    // public function setFolderUploadFile(string $srcDirectory = "", string $nameFolder = "")
    // {
    //     if (!empty($srcDirectory)) {
    //         // return __DIR__ . ""
    //     }
    // }

    public function archive(int $id, int $isActive = 0)
    {
        if (!$this->getPostById($id)) {
            echo "Данный пост не существует!";
            return;
        }

        $stmt = $this->db->prepare("UPDATE `posts` SET `is_active` = ? WHERE `id` = ?");
        $stmt->execute([$isActive, $id]);

        if ($stmt->rowCount() > 0) {
            echo "Пост помещён в архив";
            return true;
        }

        echo "Ошибка. Не удалось поместить пост в архив";
        return false;
    }

    public function unarchive(int $id, int $isActive = 1)
    {
        if (!$this->getPostById($id)) {
            echo "Данный пост не существует!";
            return;
        }

        $stmt = $this->db->prepare("UPDATE `posts` SET `is_active` = ? WHERE `id` = ?");
        $stmt->execute([$isActive, $id]);

        if ($stmt->rowCount() > 0) {
            echo "Пост активен вновь";
            return true;
        }

        echo "Ошибка. Не удалось поместить пост из архив";
        return false;
    }

    // Геттеры

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
