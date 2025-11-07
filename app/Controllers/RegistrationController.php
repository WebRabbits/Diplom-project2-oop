<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\ValidationService;
use League\Plates\Engine;
use Exception;

class RegistrationController
{
    private UserRepository $userRepo;
    private ValidationService $validationData;
    private $validationResult;
    private $template = null;
    
    public function __construct(UserRepository $userRepo, ValidationService $validate, Engine $engine)
    {
        $this->userRepo = $userRepo;
        $this->validationData = $validate;
        $this->template = $engine;
    }

    public function showRegistration()
    {
        $errors = $_SESSION["errors"] ?? [];
        $old = $_SESSION["old"] ?? [];
        unset($_SESSION["errors"], $_SESSION["old"]);
        
        echo $this->template->render("registration", compact("errors", "old"));
    }

    public function registration()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /registration");
            exit();
        }

            $data = [
                "email" => trim($_POST["email"]) ?? "",
                "password" => trim($_POST["password"]) ?? "",
                "username" => trim(htmlspecialchars($_POST["username"])) ?? ""
            ];

            $this->validationResult = $this->validationData->validate($data, "registration");
            if (!$this->validationResult->passed()) {
                $this->validationResult->addErrorException("Данные заполнены некорректно");
                $_SESSION["errors"] = $this->validationResult->errors();
                $_SESSION["old"] = $data;
                header("Location: /registration");
                exit();
            }

            if ($this->validationResult->passed()) {
                if($this->userRepo->findByEmail($data["email"])) {
                    $this->validationData->addErrorException("Такой пользователь уже существует");
                    $_SESSION["errors"] = $this->validationResult->errors();
                    $_SESSION["old"] = $data;
                    header("Location: /registration");
                    exit();
                }

                $user = $this->userRepo->create($data["email"], $data["password"], $data["username"]);
                $_SESSION["reg_complete"] = "Вы успешно зарегистрировались";
                header("Location: /auth");
                exit();
            }
        
    }
}
