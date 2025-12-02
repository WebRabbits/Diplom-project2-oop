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
    private PostId $id;
    private CreatorId $creatorId;
    private Title $title;
    private Description $description;
    private ImagePost $imagePost;
    private Status $status;
    private ?DateTime $publishedTime = null;

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

    public static function createFromData(PostId $id, CreatorId $creatorId, Title $title, Description $description, ImagePost $imagePost, Status $status, ?DateTime $publishedTime = null) {
        $updatedPost = new self($id, $creatorId, $title, $description, $imagePost);
        $updatedPost->status = $status;
        $updatedPost->publishedTime = $publishedTime;
        return $updatedPost;
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
        // dd($this->status->isPublished());
        // dd($this->status->isDraft());

        if ($this->status->isPublished()) {
            throw new DomainException("You cannot change the post status to Published. The post has already been published");
        }

        if ($this->status->isArchived()) {
            throw new DomainException("Post status cannot be changed to Published. The post is archived");
        }

        $this->status = Status::published();
        $this->publishedTime = new DateTime();

        return [
            "status" => $this->status,
            "publishedTime" => $this->publishedTime
        ];
    }


    public function draft()
    {
        if ($this->status->isDraft()) {
            throw new DomainException("You cannot change the post status to Draft. The post has already been draft");
        }

        $this->status = Status::draft();

        return $this->status;
    }

    public function archived()
    {
        if ($this->status->isArchived()) {
            throw new DomainException("You cannot change the post status to Archived. The post has already been archived");
        }

        $this->status = Status::archived();

        return $this->status;
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

    public function isDraftPost(){
        return $this->status->isDraft();
    }

    public function isPublishedPost() {
        return $this->status->isPublished();
    }

    public function isArchivedPost() {
        return $this->status->isArchived();
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
