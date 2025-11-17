<?php 

namespace App\Models\ValueObject;

use DomainException;

class Password{
    private string $hash;

    public function __construct(string $hash) {
        if(empty($hash)) {
            throw new DomainException("Password cannot be empty");
        }

        if(strlen($hash) <= 6) {
            throw new DomainException("Password must be at less 6 characters");
        }

        $this->hash = $hash;
    }

    public function getHash() {
        return $this->hash;
    }

    public static function makePasswordHash(string $password) {
        return new self(password_hash($password, PASSWORD_DEFAULT));
    }

    public function verify(string $password) {
        return password_verify($password, $this->hash);
    }

}

?>