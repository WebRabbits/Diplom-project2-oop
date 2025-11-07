<?php

namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Services\ValidationService;
use League\Plates\Engine;

class UserActionPostController
{
    private PostRepository $postRepo;
    private ValidationService $validationData;
    private Engine $engine;
    private $template;
    private $previousPage;


    public function __construct(PostRepository $postRepo, ValidationService $validate, Engine $engine)
    {
        $this->postRepo = $postRepo;
        $this->validationData = $validate;
        $this->template = $engine;
        $this->previousPage = $_SERVER["HTTP_REFERER"] ?? "/";
    }

    public function delete($idPost)
    {
        if ($this->postRepo->delete((int) $idPost["id"])) {
            $_SESSION["success"] = "Пост успешно удалён";
            header("Location: /");
            exit();
        }
    }
    public function active($idPost)
    {
        $post = $this->getCurrentPost((int) $idPost["id"]);
        if ($post->getIsActive()) {
            $this->validationData->addErrorException("Нельзя активировать уже активный пост");
            $_SESSION["errors"] = $this->validationData->errors();
            header("Location: " . $this->previousPage);
            exit();
        }

        $this->postRepo->makeActive((int) $idPost["id"]);
        $_SESSION["success"] = "Пост успешно активирован";
        header("Location: /");
    }
    public function inactive($idPost)
    {
        $post = $this->getCurrentPost((int) $idPost["id"]);
        if (!$post->getIsActive()) {
            $this->validationData->addErrorException("Нельзя добавить пост в архив, так как он уже архивирован");
            $_SESSION["errors"] = $this->validationData->errors();
            header("Location: " . $this->previousPage);
            exit();
        }

        $this->postRepo->makeInactive((int) $idPost["id"]);
        $_SESSION["success"] = "Пост успешно добавлен в архив";
        header("Location: /");
    }

    public function getCurrentPost($idPost)
    {
        return $this->postRepo->findById($idPost);
    }
}
