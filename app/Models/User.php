<?php

namespace App\Models;

use App\Models\ValueObject\Email;
use App\Models\ValueObject\Password;
use App\Models\ValueObject\ChangePasswordRequest;
use App\Models\ValueObject\UserId;
use App\Models\ValueObject\Username;
use App\Models\ValueObject\UserStatus;
use DomainException;

class User
{

    // const STATUS_ACTIVATE = "active";
    // const STATUS_DISABLED = "disabled";
    // const STATUS_PENDING = "pending";
    // private ?int $id = null;
    // private string $email;
    // private string $password; 
    // private string $username;
    // private string $status;

    private int $id;
    private UserId $secondaryId;
    private Email $email;
    private Password $password;
    private Username $username;
    private UserStatus $status;


    public function __construct(int $id, UserId $secondaryId, Email $email, Password $password, Username $username, UserStatus $status)
    {

        $this->id = $id;
        $this->secondaryId = $secondaryId;
        $this->email = $email;
        $this->password = $password;
        $this->username = $username;
        $this->status = $status;
    }

    // Внутренний метод, который используется для создания независимого внутреннего объекта модели класса User.
    // Метод используется внутри репозитория для создания реального объекта модели класса User с уже реальными полученными данными из БД.
    public static function createUser(int $id, UserId $secondaryId, Email $email, Password $password, Username $username): User
    {
        return new self($id, $secondaryId, $email, $password, $username, UserStatus::pending());
    }

    // Метод используется в контроллере для создания объекта модели класса User на основе полученных данных от пользователя в момент регистрации. Собирает объект модели с уже реальными обработанными данными.
    // После чего, передаёт из в метод create() внутри репозитория для записи данных в БД.
    public static function register(Email $email, Password $password, Username $username)
    {
        return new self(0, UserId::newId(), $email, $password, $username, UserStatus::pending());
    }

    public function changeEmail(Email $newEmail): void
    {
        if ($this->email->equals($newEmail)) {
            throw new DomainException("New email must be different current email");
        }

        $this->email = $newEmail;
    }

    public function changePassword(ChangePasswordRequest $requestChangePassword): void
    {


        if (!$this->password->verify($requestChangePassword->getCurrentPassword())) {
            throw new DomainException("Current password is incorrect");
        }

        if ($this->password->verify($requestChangePassword->getNewPassword())) {
            throw new DomainException("New password cannot be same as current password");
        }

        $this->password = Password::makePasswordHash($requestChangePassword->getNewPassword());
    }

    public function changeUsername(Username $username): void
    {
        $this->username = $username;
    }

    public function activate()
    {
        if ($this->status->canActive()) {
            throw new DomainException("User cannot be activated because the user is already active");
        }

        $this->status = UserStatus::active();
    }

    public function disabled()
    {
        if ($this->status->canDisable()) {
            throw new DomainException("User cannot be disabled because thee user is already status disable");
        }

        $this->status = UserStatus::disable();
    }


    public function getId()
    {
        return $this->id;
    }
    public function getSecondaryId(): UserId|null
    {
        return $this->secondaryId;
    }
    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }
}
