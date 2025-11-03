<?php 

namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Services\ValidationService;

class UserActionPostController{
    private PostRepository $postRepo;
    private ValidationService $validationData;

    public function __construct(PostRepository $postRepo, ValidationService $validate){
        $this->postRepo = $postRepo;
        $this->validationData = $validate;
    }

    public function handleActions() {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $idPost = $_POST["id"];
            $action = $_POST["action"];
        }

        if(empty($idPost) || empty($action)) {
            $this->validationData->addErrorException("Переданы некорректные данные");
            $errors = $this->validationData->errors();
            header("Location: /posts/edit/$idPost");
            include(__DIR__ . "/../Views/edit.php");
            return;
        }

        switch($action) {
            case "delete": {
                return $this->delete($idPost);
            }
            case "active": {
                return $this->active($idPost);
            }
            case "inactive": {
                return $this->inactive($idPost);
            }
            default: {
                $this->validationData->addErrorException("Некорректные действия");
                $errors = $this->validationData->errors();
                include(__DIR__ . "/../Views/edit.php");
                return;
            }
        }
    }

    public function delete($idPost){
        if($this->postRepo->delete($idPost)){
            header("Location: /posts");
        }
    }
    public function active($idPost){
        $post = $this->getCurrentPost($idPost);
        if($post->getIsActive()) {
            $this->validationData->addErrorException("Нельзя активировать уже активный пост");
            $errors = $this->validationData->errors();
            include(__DIR__ . "/../Views/edit.php");
            return;
        }

        $this->postRepo->makeActive($idPost);
        header("Location: /posts");
    }
    public function inactive($idPost){
        $post = $this->getCurrentPost($idPost);
        if(!$post->getIsActive()){
            $this->validationData->addErrorException("Нельзя добавить в архив пост, так как он уже архивирован");
            $errors = $this->validationData->errors();
            include(__DIR__. "/../Views/edit.php");
            return;
        }

        $this->postRepo->makeInactive($idPost);
        header("Location: /posts");
    }

    public function getCurrentPost($idPost) {
        return $this->postRepo->findById($idPost);
    }
}

?>