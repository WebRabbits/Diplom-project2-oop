<?php 

namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Services\ValidationService;

class AddPostController{
    private PostRepository $postRepo;
    private ValidationService $validationData;
    private $post;
    private $validationResult;

    public function __construct(PostRepository $postRepo, ValidationService $validate){
        $this->postRepo = $postRepo;
        $this->validationData = $validate;
    }

    public function show(){
        include(__DIR__ . "/../Views/add.php");
    }

    public function addNewPost(){
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            // dd($_POST);
            $title = trim(htmlspecialchars($_POST["title"]));
            $description = trim(htmlspecialchars($_POST["description"]));
            $imagePost = $_FILES["image"];
            // dd($imagePost);
        }
        
        $data = [
            "title" => $title ?? "",
            "description" => $description ?? "",
            "image_post" => $imagePost ?? "",
        ];

        $this->validationResult = $this->validationData->validate($data, "validatePost");
        if(!$this->validationResult->passed()){
            $errors = $this->validationData->errors();
            include(__DIR__ . "/../Views/add.php");
            return;
        }

        if($this->validationResult->passed()) {
            $this->postRepo->create($_SESSION["user"]["idUser"], $title, $description, $imagePost);
            header("Location: /posts");
            return;
        }
    }
}

?>