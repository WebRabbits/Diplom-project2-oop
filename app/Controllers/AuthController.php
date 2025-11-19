<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\ValidationService;
use App\Services\PasswordHasher;
use League\Plates\Engine;
use App\Models\ValueObject\Email;
use App\Models\ValueObject\Password;

class AuthController
{
    private UserRepository $userRepo;
    private ValidationService $validationData;
    private PasswordHasher $hasher;
    private Engine $template;
    private $validationResult;

    public function __construct(UserRepository $userRepo, ValidationService $validate, PasswordHasher $hasher, Engine $engine)
    {
        $this->userRepo = $userRepo;
        $this->validationData = $validate;
        $this->hasher = $hasher;
        $this->template = $engine;
    }

    public function showAuth()
    {
        $errors = $_SESSION["errors"] ?? [];
        $old = $_SESSION["old"] ?? [];
        unset($_SESSION["errors"], $_SESSION["old"]);

        echo $this->template->render("auth", compact("errors", "old"));
    }

    public function auth()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /auth");
            exit();
        }

        $data = [
            "email" => trim($_POST["email"]) ?? "",
            "password" => trim($_POST["password"]) ?? ""
        ];

        $this->validationResult = $this->validationData->validate($data, "auth");

        if (!$this->validationResult->passed()) {
            $this->validationData->addErrorException("Данные введены некорректно!");
            $_SESSION["errors"] = $this->validationResult->errors();
            $_SESSION["old"] = $data;
            header("Location: /auth");
            exit();
        }

        if ($this->validationResult->passed()) {
            $email = new Email($data["email"]);
            $password = Password::makePasswordHash($data["password"]);

            $user = $this->userRepo->findByEmail($email);

            if (!$user) {
                $this->validationData->addErrorException("Пользователя с данным Email не существует!");
                $_SESSION["errors"] = $this->validationResult->errors();
                $_SESSION["old"] = $data;
                header("Location: /auth");
                exit();
            } else {
                $isValidPassword = $user->getPassword()->verify($data["password"]);

                if (!$isValidPassword) {
                    $this->validationData->addErrorException("Неверно указан пароль!");
                    $_SESSION["errors"] = $this->validationResult->errors();
                    $_SESSION["old"] = $data;
                    header("Location: /auth");
                    exit();
                }
            }

            if ($user && $isValidPassword) {
                setcookie("login", $user->getUsername()->getValue(), time() + 3600, "/", "", true, true);
                session_regenerate_id(true);

                $_SESSION["user"] = [
                    "idUser" => $user->getId(),
                    "email" => $user->getEmail()->getValue(),
                    "username" => $user->getUsername()->getValue()
                ];

                header("Location: /profile/" . $user->getId());
                exit();
            }
        }
    }

    public function logout()
    {
        setcookie("login", "", time() - 3600, "/");

        session_unset();
        $_SESSION = [];
        session_destroy();

        header("Location: /auth");
        exit();
    }
}
