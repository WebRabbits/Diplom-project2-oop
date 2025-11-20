<?php 
// Метка времени формата "20225-11-18 23:32:11" должна выставляться в момент публикации статусы. То есть, в перевод статуса const STATUS_PUBLISH = 2
// В всех остальных случая, дата публикации - НЕ ИЗМЕНЯЕТСЯ (остаётся только сама дата публикации)

namespace App\Models\ValueObject\Post;

use DomainException;

class Status{
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_ARCHIVED = 3;
    private int $value;

    public function __construct(int $value) {
        if(empty($value)) {
            throw new DomainException("Status cannot be empty");
        }

        if(!in_array($value, [self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_ARCHIVED])) {
            throw new DomainException("Invalid Status Post value");
        }

        $this->value = $value;
    }

    public static function published() {
        return new self(self::STATUS_PUBLISHED);
    }

    public static function draft() {
        return new self(self::STATUS_DRAFT);
    }

    public static function archived() {
        return new self(self::STATUS_ARCHIVED);
    }

    public function isDraft(){
        return $this->value === self::STATUS_DRAFT;
    }

    public function isPublished(){
        return $this->value === self::STATUS_PUBLISHED;
    }

    public function isArchived() {
        return $this->value === self::STATUS_ARCHIVED;
    }

    public function canDraft(){
        return $this->isPublished() || $this->isArchived();
    }

    public function canPublished() {
        return $this->isDraft();
    }

    public function getValue(){
        return $this->value;
    }

    public function equals(Status $otherObject) {
        return $this->value === $otherObject->value;
    }

}


?>