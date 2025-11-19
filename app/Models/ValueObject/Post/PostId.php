<?php 

namespace App\Models\ValueObject\Post;

use App\Models\Post;
use Ramsey\Uuid\Uuid;
use DomainException;
class PostId{
    private string $value;

    public function __construct(string $value) {
        if(empty($value)) {
            throw new DomainException("Post ID cannot be empty");
        }

        $this->value = $value;
    }

    public static function newId() {
        $uuid = Uuid::uuid4();
        return new self($uuid);
    }

    public function getValue(){
        return $this->value;
    }

    public function equals(PostId $otherObject) {
        return $this->value === $otherObject->value;
    }
}

?>