<?php 

namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Services\ValidationService;

class EditPostController{
    private PostRepository $postRepo;
    private ValidationService $validationData;
    private $validationResult;

    public function __construct(PostRepository $postRepo, ValidationService $validate) {
        $this->postRepo = $postRepo;
        $this->validationData = $validate;
    }

    public function show(array $vars) {
        $postId = (int) $vars["id"];
        
        $post = $this->postRepo->findById($postId);
        // dd($post);
        include(__DIR__ . "/../Views/edit.php");
    }

    public function update(array $vars) {
        $idPost = $vars["id"];
        $currentUserId = $_SESSION["user"]["idUser"];

        $post = $this->postRepo->findById($idPost);
        // dd($post);

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = trim(htmlspecialchars($_POST["title"]));
            $description = trim(htmlspecialchars($_POST["description"]));
            $imagePost = $_FILES["image"];
        }

        $data = [
            "title" => $title,
            "description" => $description,
            "image_post" => $imagePost
        ];

        $this->validationResult = $this->validationData->validate($data, "validatePost");

        if(!$this->validationResult->passed()) {
            $errors = $this->validationData->errors();
            include(__DIR__ . "/../Views/edit.php");
            return;
        }

        if($this->validationResult->passed()) {
            // dd($post);
            // dd($_SESSION["user"]);
            // die;
            if(!$post->isOwner($currentUserId)){
                $this->validationData->addErrorException("Редактировать пост может только создатель");
                $errors = $this->validationData->errors();
                include(__DIR__ . "/../Views/edit.php");
                return ;
            }

            if(empty($title) && empty($description) && empty($imagePost["tmp_name"])){
                $this->validationData->addErrorException("Все поля пустые! Заполните хотя бы одно поле");
                $errors = $this->validationData->errors();
                include(__DIR__ . "/../Views/edit.php");
                return;
            }

            $this->postRepo->update($post->getId(), $post->getIdCreator(), $title, $description, $imagePost);
            header("Location: /posts");
            dd($post);
        }
    }
}

?>