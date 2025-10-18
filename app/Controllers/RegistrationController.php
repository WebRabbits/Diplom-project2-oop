<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\ValidationService;
use Exception;

class RegistrationController
{
    private UserRepository $userRepo;
    private ValidationService $validationData;
    private $validationResult;

    public function __construct(UserRepository $userRepo, ValidationService $validate)
    {
        $this->userRepo = $userRepo;
        $this->validationData = $validate;
    }

    public function showRegistration()
    {
        include(__DIR__ . "/../Views/registration.php");
    }

    public function registration()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim($_POST["email"]) ?? "";
            $password = trim($_POST["password"]) ?? "";
            $username = trim(htmlspecialchars($_POST["username"])) ?? "";

            $data = [
                "email" => $email,
                "password" => $password,
                "username" => $username
            ];

            $this->validationResult = $this->validationData->validate($data);
            if (!$this->validationResult->passed()) {
                // echo "Данные неверны!";
                $this->validationResult->addErrorException("Данные заполнены некорректно");
                $errors = $this->validationResult->errors();
                include(__DIR__ . "/../Views/registration.php");
                return;
            }

            if ($this->validationResult->passed()) {
                $existingEmail = $this->userRepo->findByEmail($email);
                if (!$existingEmail) {
                    $user = $this->userRepo->create($email, $password, $username);
                    header("Location: /auth");
                    exit();
                    // dd($user);
                    // die;
                } else {
                    $this->validationData->addErrorException("Такой пользователь уже существует");
                    $errors = $this->validationResult->errors();
                    include(__DIR__ . "/../Views/registration.php");
                    return;
                }
            }
        }
    }
}
