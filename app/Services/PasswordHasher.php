<?php

namespace App\Services;

class PasswordHasher{
    private string $resultHash;
    private bool $resultHashCheck;

    public function passwordHash($password) {
        $this->resultHash = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    public function passwordVerify($passwordCheck, $passwordHashCheck) {
        $this->resultHashCheck = password_verify($passwordCheck, $passwordHashCheck);
        return $this;
    }

    public function getPasswordHash(): string{
        return $this->resultHash;
    }

    public function getPasswordHashCheck(): bool{
        return $this->resultHashCheck;
    }
}

?>