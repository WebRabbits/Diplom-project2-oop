<?php 

namespace App\Models\ValueObject\Post;

use DomainException;

class Title{
    private string $value;

    public function __construct(string $value) {
        if(empty($value)) {
            throw new DomainException("Title cannot be empty");
        }

        if(strlen($value) < 10 || strlen($value) > 100) {
            throw new DomainException("The title must between 10 and 100 characters");
        }

        $this->value = trim($value);
    }

    public function getValue(){
        return $this->value;
    }

    public function equals(Title $otherObject) {
        return $this->value === $otherObject->value;
    }
}

?>