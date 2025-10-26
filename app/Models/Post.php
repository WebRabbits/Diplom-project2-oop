<?php

namespace App\Models;
class Post
{
    const STATUS_ACTIVATE = true;
    const STATUS_DEACTIVATE = false;
    private ?int $id = null;
    private ?int $idCreator = null;
    private string $title;
    private string $description;
    private string $datePublished;
    private string $imagePost;
    private bool $isActive;

    public function __construct(?int $id = null, ?int $idCreator = null, string $title = "", string $description = "", string $date = "", string $imagePost = "", bool $isActive = true)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException("ID cannot be empty");
        }
        if (empty($idCreator)) {
            throw new \InvalidArgumentException("ID Creator cannot be empty");
        }
        if (empty($title)) {
            throw new \InvalidArgumentException("Title cannot be empty");
        }
        if (empty($description)) {
            throw new \InvalidArgumentException("Description cannot be empty");
        }
        if (empty($date)) {
            throw new \InvalidArgumentException("Date cannot be empty");
        }
        if (empty($imagePost)) {
            throw new \InvalidArgumentException("imgPost cannot be empty");
        }
        // if (empty($isActive)) {
        //     throw new \InvalidArgumentException("isActive cannot be empty");
        // }


        $this->id = $id;
        $this->idCreator = $idCreator;
        $this->title = $title;
        $this->description = $description;
        $this->datePublished = $date;
        $this->imagePost = $imagePost;
        $this->isActive = $isActive;
    }

    public static function getPost(int $id, int $idCreator, string $title, string $description, string $datePublished, string $imagePost, bool $isActive): Post
    {
        return new self($id, $idCreator, $title, $description, $datePublished, $imagePost, $isActive);
    }

    // Проверка на ID создателя самого пользователя, который, если является создателем поста - может производить его редактирование
    public function isOwner(int $userId) {
        return $userId === $this->getIdCreator();
    }

    public function updateTitle($title) {
        if(empty($title)) {
            throw new \InvalidArgumentException("Title cannot be empty");
        }

        if(strlen($title) < 7 || strlen($title) > 50) {
            throw new \InvalidArgumentException("Title must be between 7 and 50 characters");
        }

        $this->title = $title;
    }

    public function updateDescription($description) {
        if(empty($description)) {
            throw new \InvalidArgumentException("Description cannot be empty");
        }

        if(strlen($description) < 10 || strlen($description) > 100) {
            throw new \InvalidArgumentException("Description must be between 10 and 100 characters");
        }

        $this->description = $description;
    }

    public function updateImagePost($imagePost) {
        if(empty($imagePost)) {
            throw new \InvalidArgumentException("Image path cannot be empty");
        }

        $this->imagePost = $imagePost;
    }

    public function isActivate(){
        if($this->isActive == self::STATUS_ACTIVATE) {
            throw new \InvalidArgumentException("Cannot be activated. Pst is already active");
        }

        $this->isActive = self::STATUS_ACTIVATE;
    }

    public function isDeactivate() {
        if($this->isActive == self::STATUS_DEACTIVATE) {
            throw new \InvalidArgumentException("Cannot be deactivated. Post is already deactivate");
        }

        $this->isActive = self::STATUS_DEACTIVATE;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getIdCreator()
    {
        return $this->idCreator;
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

    // public function addPost(string $title, string $description, array $imagePost)
    // {
    //     if (empty($title) || empty($description)) {
    //         echo "Поле не может быть пустым";
    //         return;
    //     }

    //     if (!isset($imagePost)) {
    //         echo "Выберите картинку!";
    //         return;
    //     }

    //     $imagePost = $this->post->uploadImage($imagePost);
    //     // dd($imagePost);

    //     if (is_null($imagePost)) return;

    //     $lastInsertID = $this->post->create($idCreator, $title, $description, $imagePost);

    //     if ($lastInsertID) {
    //         $this->id = $lastInsertID;
    //         $newPost = $this->post->findById($this->id);
    //         $this->id = $newPost->id;
    //         $this->title = $newPost->title;
    //         $this->description = $newPost->description;
    //         $this->datePublished = $newPost->date;
    //         $this->imagePost = $newPost->image_post;
    //         $this->isActive = $newPost->is_active;

    //         echo "Пост успешно добавлен";
    //         return true;
    //     }

    //     echo "Ошибка. Не удалось добавить пост";
    //     return false;
    // }
    // public function editPost(int $id, string $title = "", string $description = "", array $imagePost = [])
    // {
    //     $currentPost = $this->post->findById($id);


    //     if (!$currentPost) {
    //         return;
    //     }

    //     if (empty($title) && empty($description) && empty($imagePost)) {
    //         echo "Все поля не могут быть пустыми. Измените значение хотя бы в одном поле!";
    //         return;
    //     }

    //     if (!empty($imagePost)) {
    //         $imagePost = $this->post->uploadImage($imagePost);
    //         $this->post->deleteImage($id);
    //     } else {
    //         $imagePost = $currentPost->image_post;
    //     }

    //     $result = $this->post->update($id, $idCreator, $title, $description, $imagePost);

    //     if ($result > 0) {
    //         echo "Пост успешно изменён";
    //         return true;
    //     }

    //     echo "Ошибка. Не удалось обновить запись по посту";
    //     return false;
    // }
    // public function deletePost(int $id)
    // {
    //     if (!$this->post->findById($id)) {
    //         return;
    //     }

    //     $this->post->deleteImage($id);
    //     $result = $this->post->delete($id);
    //     // die;
    //     if ($result > 0) {
    //         echo "Пост успешно удалён";
    //         return true;
    //     }

    //     echo "Ошибка. Не удалось удалить пост";
    //     return false;
    // }
    // public function getAllPosts()
    // {
    //     return $this->post->getAll();
    // }

    // public function getPostById(int $id)
    // {
    //     $this->result = $this->post->findById($id);

    //     if (!$this->result()) {
    //         echo "Данный пост не существует";
    //         return;
    //     }

    //     return $this->result();
    // }

    // public function archive(int $id)
    // {
    //     if (!$this->post->findById($id)) {
    //         return;
    //     }

    //     $result = $this->post->makeInactive($id);

    //     if ($result > 0) {
    //         echo "Пост помещён в архив";
    //         return true;
    //     }

    //     echo "Ошибка. Не удалось поместить пост в архив";
    //     return false;
    // }

    // public function unarchive(int $id)
    // {
    //     if (!$this->post->findById($id)) {
    //         return;
    //     }

    //     $result = $this->post->makeActive($id);

    //     if ($result > 0) {
    //         echo "Пост активен вновь";
    //         return true;
    //     }

    //     echo "Ошибка. Не удалось переместить пост из архив";
    //     return false;
    // }

    //// Геттеры


    // public function result()
    // {
    //     return $this->result;
    // }
}
