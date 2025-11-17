<?php 

namespace App\Models\ValueObject;

use DomainException;

class UserStatus{
    const STATUS_ACTIVATE = "active";
    const STATUS_DISABLED = "disable";
    const STATUS_PENDING = "pending";

    private string $value;

    public function __construct(string $value) {
        $arrayStatus = [self::STATUS_ACTIVATE, self::STATUS_DISABLED, self::STATUS_PENDING];

        if(empty($value)) {
            throw new DomainException("Status cannot be empty");
        }

        if(!in_array($value, $arrayStatus)){
            throw new DomainException("The assigned status does not exist");
        }

        $this->value = $value;
    }

    public static function active() {
        return new self(self::STATUS_ACTIVATE);
    }

    public static function disable() {
        return new self(self::STATUS_DISABLED);
    }

    public static function pending() {
        return new self(self::STATUS_PENDING);
    }

    public function isActive(){
        return $this->value === self::STATUS_ACTIVATE;
    }

    public function isDisable() {
        return $this->value === self::STATUS_DISABLED;
    }

    public function isPending() {
        return $this->value === self::STATUS_PENDING;
    }

    public function canActive() {
        return $this->isDisable() || $this->isPending();
    }

    public function canDisable() {
        return $this->isActive();
    }

    public function getValue(){
        return $this->value;
    }

    public function equals(self $otherObject){
        return $this->value === $otherObject->value;
    }


}

?>