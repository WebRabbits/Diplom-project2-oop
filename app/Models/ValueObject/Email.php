<?php 

namespace App\Models\ValueObject;

use DomainException;

class Email{
    private string $value;

    public function __construct(string $value) {
        if(empty($value)) {
            throw new DomainException("Email cannot be empty");
        }

        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException("Invalid email format");
        }

        $this->value = trim($value);
    }

    public function getValue() {
        return $this->value;
    }

    public function equals(self $otherObject) {
        return $this->value === $otherObject->value;
    }
}

?>