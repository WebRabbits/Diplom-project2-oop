<?php 

namespace App\Models\ValueObject\Post;

use DomainException;

class Description{
    private string $value;

    public function __construct(string $value) {
        if(empty($value)) {
            throw new DomainException("Description cannot be empty");
        }

        if(strlen($value) < 10 || strlen($value) > 100000) {
            throw new DomainException("The password must between 10 and 100000 characters");
        }

        $this->value = trim($value);
    }

    public function getValue() {
        return $this->value;
    }

    public function equals(Description $otherObject){
        return $this->value === $otherObject->value;
    }
}

?>