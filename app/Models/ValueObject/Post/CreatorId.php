<?php 

namespace App\Models\ValueObject\Post;

use Ramsey\Uuid\Uuid;
use DomainException;

class CreatorId{
    private string $value;

    public function __construct(string $value) {
        if(empty($value)) {
            throw new DomainException("Creator ID cannot be empty");
        }

        // if(!Uuid::isValid($value)) {
        //     throw new DomainException("Invalid Creator ID value. Must be a valid Uuid");
        // }

        $this->value = $value;
    }

    public function getValue(){
        return $this->value;
    }

    public function equals(CreatorId $otherObject) {
        return $this->value === $otherObject->value;
    }

    
}

?>