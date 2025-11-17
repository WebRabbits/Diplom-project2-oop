<?php 

namespace App\Models\ValueObject;

use DomainException;

class ChangePasswordRequest {
    private string $currentPassword;
    private string $newPassword;
    private string $repeatPassword;

    public function __construct(string $currentPassword, string $newPassword, string $repeatPassword) {
        if(empty($currentPassword) || empty($newPassword) || empty($repeatPassword)) {
            throw new DomainException("Fields cannot be empty");
        }

        if($newPassword !== $repeatPassword) {
            throw new DomainException("Password values do not much");
        }

        if(strlen($newPassword) <= 6 || strlen($repeatPassword) <= 6) {
            throw new DomainException("New password and Repeat password must be at least 6 characters long");
        }

        $this->currentPassword = $currentPassword;
        $this->newPassword = $newPassword;
        $this->repeatPassword = $repeatPassword;
    }

    public function getCurrentPassword(): string {
        return $this->currentPassword;
    }

    public function getNewPassword(): string {
        return $this->newPassword;
    }

    public function getRepeatPassword(): string {
        return $this->repeatPassword;
    }
}

?>