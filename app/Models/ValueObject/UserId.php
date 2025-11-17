<?php 

namespace App\Models\ValueObject;

use Ramsey\Uuid\Uuid;
use DomainException;

class UserId{
    private string $value;

    public function __construct(string $value) {
        if(empty($value)) {
            throw new DomainException("User ID cannot be empty");
        }

        if(is_null($value) || $value <= 0) {
            throw new DomainException("User ID must be positive value");
        }

        $this->value = $value;
    }

    public static function newId() {
        $uuid = Uuid::uuid4();
        return new self ($uuid);
    }

    public function getValue() {
        return $this->value;
    }

    public function equals(self $otherObject) {
        return $this->value === $otherObject->value;
    }
}

?>