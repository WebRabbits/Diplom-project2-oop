<?php

namespace App\Models;

use App\Models\ValueObject\Post\CreatorId;
use App\Models\ValueObject\Post\Description;
use App\Models\ValueObject\Post\ImagePost;
use App\Models\ValueObject\Post\Status;
use App\Models\ValueObject\Post\Title;
use App\Models\ValueObject\Post\PostId;
use DateTime;
use DomainException;

class Post
{
    // const STATUS_ACTIVATE = true;
    // const STATUS_DEACTIVATE = false;
    // private ?int $id = null;
    // private ?int $idCreator = null;
    // private string $title;
    // private string $description;
    // private string $datePublished;
    // private string $imagePost;
    // private bool $isActive;

    private PostId $id;
    private CreatorId $creatorId;
    private Title $title;
    private Description $description;
    private ImagePost $imagePost;
    private Status $status;
    private ?DateTime $publishedTime;

    public function __construct(PostId $id, CreatorId $creatorId, Title $title, Description $description, ImagePost $imagePost)
    {

        $this->id = $id;
        $this->creatorId = $creatorId;
        $this->title = $title;
        $this->description = $description;
        $this->imagePost = $imagePost;
        $this->status = new Status(Status::STATUS_DRAFT);
    }

    public static function createPost(PostId $id, CreatorId $creatorId, Title $title, Description $description, ImagePost $imagePost): Post
    {
        return new self($id, $creatorId, $title, $description, $imagePost);
    }

    public static function newPost(CreatorId $creatorId, Title $title, Description $description, ImagePost $imagePost)
    {
        return new self(PostId::newId(), $creatorId, $title, $description, $imagePost);
    }

    // Проверка на ID создателя самого пользователя, который, если является создателем поста - может производить его редактирование
    public function isOwner(CreatorId $userId)
    {
        return $this->creatorId->equals($userId);
    }

    public function updateTitle(Title $newTitle)
    {
        $this->title = $newTitle;
    }

    public function updateDescription(Description $newDescription)
    {
        $this->description = $newDescription;
    }

    public function updateImagePost(ImagePost $newImagePost)
    {
        $this->imagePost = $newImagePost;
    }

    public function publish()
    {
        if (!$this->status->canPublished()) {
            throw new DomainException("You cannot change the post status to Published. The post has already been published");
        }

        if ($this->status->isArchived()) {
            throw new DomainException("Post status cannot be changed to Published. The post is archived");
        }

        $this->status = Status::published();
        $this->publishedTime = new DateTime();
    }


    public function draft()
    {
        if (!$this->status->canDraft()) {
            throw new DomainException("You cannot change the post status to Published. The post has already been published or archived");
        }

        $this->status = Status::draft();
    }

    public function archived()
    {
        if ($this->status->isArchived()) {
            throw new DomainException("You cannot change the post status to Archived. The post has already been archived");
        }

        $this->status = Status::archived();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatorId()
    {
        return $this->creatorId;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getImagePost()
    {
        return $this->imagePost;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getPublishedTime()
    {
        return $this->publishedTime;
    }

    public function formatDateTime()
    {
        return $this->publishedTime->format("Y-m-d H:i:s") ?? "";
    }
}
