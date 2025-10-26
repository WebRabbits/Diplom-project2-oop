<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\ValidationService;
use App\Services\PasswordHasher;

class AuthController
{
    private UserRepository $userRepo;
    private ValidationService $validationData;
    private PasswordHasher $hasher;
    private $validationResult;

    public function __construct(UserRepository $userRepo, ValidationService $validate, PasswordHasher $hasher)
    {
        $this->userRepo = $userRepo;
        $this->validationData = $validate;
        $this->hasher = $hasher;
    }

    public function showAuth()
    {
        include(__DIR__ . "/../Views/auth.php");
    }

    public function auth()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim($_POST["email"]);
            $password = trim($_POST["password"]);
        }

        $data = [
            "email" => $email ?? "",
            "password" => $password ?? ""
        ];

        $this->validationResult = $this->validationData->validate($data, "auth");

        if (!$this->validationResult->passed()) {
            $this->validationData->addErrorException("Данные введены некорректно!");
            $errors = $this->validationResult->errors();
            include(__DIR__ . "/../Views/auth.php");
            return;
        }

        if ($this->validationResult->passed()) {
            $user = $this->userRepo->findByEmail($email);
            // dd($user);

            if (!$user) {
                $this->validationData->addErrorException("Пользователя с данным Email не существует!");
                $errors = $this->validationResult->errors();
                include(__DIR__ . "/../Views/auth.php");
                return;
            } else {
                $isValidPassword = $this->hasher->passwordVerify($password, $user->getPassword())->getPasswordHashCheck();
                // dd($isValidPassword);

                if (!$isValidPassword) {
                    $this->validationData->addErrorException("Неверно указан пароль!");
                    $errors = $this->validationResult->errors();
                    include(__DIR__ . "/../Views/auth.php");
                    return;
                }
            }


            if ($user && $isValidPassword) {
                setcookie("login", $user->getUsername(), time() + 3600, "/", "", true, true);
                session_regenerate_id(true);

                $_SESSION["user"] = [
                    "idUser" => $user->getId(),
                    "email" => $user->getEmail(),
                    "username" => $user->getUsername()
                ];

                header("Location: /profile");
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
    }
}
