<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\ValueObject\Post\CreatorId;
use App\Models\ValueObject\Post\Description;
use App\Models\ValueObject\Post\ImagePost;
use App\Models\ValueObject\Post\PostId;
use App\Models\ValueObject\Post\Title;
use App\Repositories\PostRepository;
use App\Services\ImageUploadService;
use App\Services\ValidationService;
use League\Plates\Engine;

class EditPostController
{
    private PostRepository $postRepo;
    private ValidationService $validationData;
    private Engine $engine;
    private ImageUploadService $uploadImage;
    private $validationResult;
    private $template;

    public function __construct(PostRepository $postRepo, ValidationService $validate, Engine $engine, ImageUploadService $uploadImage)
    {
        $this->postRepo = $postRepo;
        $this->validationData = $validate;
        $this->template = $engine;
        $this->uploadImage = $uploadImage;
    }

    public function show(array $vars)
    {
        $path = $_SERVER["REQUEST_URI"];
        $postId = new PostId($vars["id"]);
        $post = $this->postRepo->findById($postId);
        $errors = $_SESSION["errors"] ?? [];
        $old = $_SESSION["old"] ?? [];
        unset($_SESSION["errors"], $_SESSION["old"]);
        // dd($post);

        echo $this->template->render("edit", compact("errors", "old", "post"));
    }

    public function update(array $vars)
    {
        $postId = new PostId($vars["id"]);
        $currentUserId = new CreatorId($_SESSION["user"]["idUser"]);
        $path = $_SERVER["REQUEST_URI"];

        $post = $this->postRepo->findById($postId);

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /profile/" . $currentUserId->getValue());
            exit();
        }

        $data = [
            "title" => trim(htmlspecialchars($_POST["title"])),
            "description" => trim(htmlspecialchars($_POST["description"])),
            "image_post" => $_FILES["image"]
        ];

        $this->validationResult = $this->validationData->validate($data, "validateEditPost");

        if (!$this->validationResult->passed()) {
            $_SESSION["errors"] = $this->validationData->errors();
            $_SESSION["old"] = $data;
            header("Location: $path");
            exit();
        }

        if ($this->validationResult->passed()) {
            if (!$post->isOwner($currentUserId)) {
                $this->validationData->addErrorException("Редактировать пост может только создатель");
                $_SESSION["errors"] = $this->validationData->errors();
                $_SESSION["old"] = $data;
                header("Location: $path");
                exit();
            }

            if (empty($data["title"]) && empty($data["description"]) && empty($data["image_post"]["tmp_name"])) {
                $this->validationData->addErrorException("Все поля пустые! Заполните хотя бы одно поле");
                $_SESSION["errors"] = $this->validationData->errors();
                $_SESSION["old"] = $data;
                header("Location: $path");
                exit();
            }

            $title = new Title($data["title"]);
            $description = new Description($data["description"]);
            $imgUploadNewImagePath = $this->uploadImage->updateImage($data["image_post"], $post->getImagePost()->getValue());

            $imagePost = !is_null($imgUploadNewImagePath) ? 
            new ImagePost($imgUploadNewImagePath, $data["image_post"]["type"]) : 
            new ImagePost($post->getImagePost()->getValue());

            $this->postRepo->update(Post::createPost($postId, $currentUserId, $title, $description, $imagePost));
            header("Location: /");
        }
    }
}
