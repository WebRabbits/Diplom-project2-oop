<?php

namespace App\Controllers;

use App\Models\ValueObject\Post\PostId;
use App\Models\ValueObject\Post\Status;
use App\Repositories\PostRepository;
use App\Services\ValidationService;
use League\Plates\Engine;

use function PHPSTORM_META\type;

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
        $post = $this->getCurrentPost((int) $idPost["id"]);
        if ($this->postRepo->delete($post)) {
            $_SESSION["success"] = "Пост успешно удалён";
            header("Location: /");
            exit();
        }
    }
    public function publish($idPost)
    {
        $post = $this->getCurrentPost((int) $idPost["id"]);
            // dd($post);
            // die;
        if ($post->getStatus()->getValue() === Status::STATUS_PUBLISHED) {
            $this->validationData->addErrorException("Нельзя активировать уже активный пост");
            $_SESSION["errors"] = $this->validationData->errors();
            header("Location: " . $this->previousPage);
            exit();
        }

        if($post->getStatus()->getValue() === Status::STATUS_ARCHIVED) {
            $this->validationData->addErrorException("Вы не можете сделать активным ранее архивированный пост");
            $_SESSION["errors"] = $this->validationData->errors();
            header("Location: " . $this->previousPage);
            exit();
        }

        extract($post->publish()); // Возвращает отдельные данные из ассоциативного массива: return $status, $publishedTime

        $this->postRepo->makePublished($status, $post->getId(), $publishedTime);
        $_SESSION["success"] = "Пост успешно получил статус \"Активирован\"";
        header("Location: /");
        exit();
    }

    public function draft($idPost) {
        $post = $this->getCurrentPost((int) $idPost["id"]);
        if($post->getStatus()->getValue() === Status::STATUS_DRAFT) {
            $this->validationData->addErrorException("Нельзя сделать пост черновиком, так как он уже в статусе \"Черновик\"");
            $_SESSION["errors"] = $this->validationData->errors();
            header("Location: " . $this->previousPage);
        }

        $changedStatus = $post->draft();
        $this->postRepo->makeDraft($changedStatus, $post->getId());
        $_SESSION["success"] = "Пост успешно получил статус \"Черновик\"";
        header("Location: /");
        exit();
    }

    public function archived($idPost)
    {
        $post = $this->getCurrentPost((int) $idPost["id"]);
        if ($post->getStatus()->getValue() === Status::STATUS_ARCHIVED) {
            $this->validationData->addErrorException("Нельзя добавить пост в архив, так как он уже архивирован");
            $_SESSION["errors"] = $this->validationData->errors();
            header("Location: " . $this->previousPage);
            exit();
        }

        $changedStatus = $post->archived();

        $this->postRepo->makeArchived($changedStatus, $post->getId());
        $_SESSION["success"] = "Пост успешно добавлен в архив";
        header("Location: /");
    }

    public function getCurrentPost($idPost)
    {
        $idPost = new PostId($idPost);
        return $this->postRepo->findById($idPost);
    }
}
