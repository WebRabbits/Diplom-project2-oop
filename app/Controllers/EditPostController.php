<?php

namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Services\ValidationService;
use League\Plates\Engine;

class EditPostController
{
    private PostRepository $postRepo;
    private ValidationService $validationData;
    private Engine $engine;
    private $validationResult;
    private $template;

    public function __construct(PostRepository $postRepo, ValidationService $validate, Engine $engine)
    {
        $this->postRepo = $postRepo;
        $this->validationData = $validate;
        $this->template = $engine;
    }

    public function show(array $vars)
    {
        $path = $_SERVER["REQUEST_URI"];
        // $postId = (int) $vars["id"];
        $post = $this->postRepo->findById((int) $vars["id"]);
        $errors = $_SESSION["errors"] ?? [];
        $old = $_SESSION["old"] ?? [];
        unset($_SESSION["errors"], $_SESSION["old"]);

        echo $this->template->render("edit", compact("errors", "old", "post"));
    }

    public function update(array $vars)
    {
        $idPost = $vars["id"];
        $currentUserId = $_SESSION["user"]["idUser"];
        $path = $_SERVER["REQUEST_URI"];

        $post = $this->postRepo->findById($idPost);

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /profile/$currentUserId");
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

            $this->postRepo->update($post->getId(), $post->getIdCreator(), $data["title"], $data["description"], $data["image_post"]);
            header("Location: /");
        }
    }
}
