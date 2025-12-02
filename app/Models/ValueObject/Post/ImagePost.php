<?php

namespace App\Models\ValueObject\Post;

use DomainException;

class  ImagePost
{
    private string $path;

    const ALLOWED_TYPES = [
        "image/png" => "png",
        "image/jpeg" => "jpg",
    ];

    public function __construct(string $path, ?string $mimeType = null)
    {
        if (empty($path)) {
            throw new DomainException("Image post cannot be empty");
        }

        if(!isset(self::ALLOWED_TYPES[$mimeType]) && !is_null($mimeType)) {
            throw new DomainException("Invalid image type. Allowed: " . implode(", ", array_values(self::ALLOWED_TYPES)));
        }

        $this->path = $path;
    }

    public function getValue()
    {
        return $this->path;
    }

    public function getMimeType() {
        return mime_content_type($this->path);
    }

    public function equals(ImagePost $otherObject) {
        return $this->path === $otherObject->path;
    }
}
