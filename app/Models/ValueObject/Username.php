<?php 

namespace App\Models\ValueObject;

use DomainException;

class Username{
    private string $value;

    public function __construct(string $value) {
        if(empty($value)) {
            throw new DomainException("Username cannot be empty");
        }

        if(strlen($value) < 3) {
            throw new DomainException("Username must be at less 3 characters");
        }

        $this->value = trim($value);
    }

    public function getValue(){
        return $this->value;
    }

    public function equals(self $otherObject){
        return $this->value === $otherObject->value;
    }
}

?>