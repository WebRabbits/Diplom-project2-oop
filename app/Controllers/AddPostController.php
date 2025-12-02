<?php 

namespace App\Controllers;

use App\Models\Post;
use App\Models\ValueObject\Post\CreatorId;
use App\Models\ValueObject\Post\Description;
use App\Models\ValueObject\Post\ImagePost;
use App\Models\ValueObject\Post\Title;
use App\Repositories\PostRepository;
use App\Services\ImageUploadService;
use App\Services\ValidationService;
use League\Plates\Engine;

class AddPostController{
    private PostRepository $postRepo;
    private ValidationService $validationData;

    private ImageUploadService $uploadImage;
    private Engine $engine;
    private $post;
    private $validationResult;
    private $template;

    public function __construct(PostRepository $postRepo, ValidationService $validate, Engine $engine, ImageUploadService $uploadImage){
        $this->postRepo = $postRepo;
        $this->validationData = $validate;
        $this->template = $engine;
        $this->uploadImage = $uploadImage;
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
            $creatorId = new CreatorId($_SESSION["user"]["idUser"]);
            $title = new Title($data["title"]);
            $description = new Description($data["description"]);

            // $imagePost = $this->uploadImage->upload($data["image_post"]);
            $imgUploadPath = $this->uploadImage->upload($data["image_post"]);
            if(!empty($imgUploadPath)) {
                $this->uploadImage->setMimeType($data["image_post"]["type"]);
            }
            $imagePost = new ImagePost($imgUploadPath, $this->uploadImage->getMimeType());

            $newPost = Post::newPost($creatorId, $title, $description, $imagePost);

            $this->postRepo->create($newPost);
            $_SESSION["success"] = "Новый пост успешно добавлен";
            header("Location: /");
            exit();
        }
    }
}

?>