<?php 

namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Services\ValidationService;
use League\Plates\Engine;

class AddPostController{
    private PostRepository $postRepo;
    private ValidationService $validationData;
    private Engine $engine;
    private $post;
    private $validationResult;
    private $template;

    public function __construct(PostRepository $postRepo, ValidationService $validate, Engine $engine){
        $this->postRepo = $postRepo;
        $this->validationData = $validate;
        $this->template = $engine;
    }

    public function show(){
        $errors = $_SESSION["errors"] ?? [];
        $old = $_SESSION["old"] ?? [];
        unset($_SESSION["errors"], $_SESSION["old"]);
        
        echo $this->template->render("add", compact("errors", "old"));
    }

    public function addNewPost(){
        if($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /posts/add");
            exit();
        }
        
        $data = [
            "title" => trim(htmlspecialchars($_POST["title"])) ?? "",
            "description" => trim(htmlspecialchars($_POST["description"])) ?? "",
            "image_post" => $_FILES["image"] ?? ""
        ];

        $this->validationResult = $this->validationData->validate($data, "validateCreatePost");
        if(!$this->validationResult->passed()){
            $this->validationData->addErrorException("Заполните поля значениями следуя инструкциям по каждому полю!");
            $_SESSION["errors"] = $this->validationData->errors();
            $_SESSION["old"] = $data;
            header("Location: /posts/add");
            exit();
        }

        if($this->validationResult->passed()) {
            $this->postRepo->create($_SESSION["user"]["idUser"], $data["title"], $data["description"], $data["image_post"]);
            $_SESSION["success"] = "Новый пост успешно добавлен";
            header("Location: /");
            exit();
        }
    }
}

?>