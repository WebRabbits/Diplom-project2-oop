<?php


namespace App\Services;

use InvalidArgumentException;

class ImageUploadService
{

    const ALLOWED_MIME_TYPES = ["image/png", "image/jpeg", "image/jpg"];
    private string $targetDirectory;
    private string $publicDirectory = "/img/posts/";
    private string $mimeType;

    public function __construct(string $targetDirectory, string $publicDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        $this->publicDirectory = $publicDirectory;
    }
    public function upload(array $uploadFile)
    {
        dd($uploadFile);
        $this->validateUpload($uploadFile);
        $this->validateMimeType($uploadFile["type"]);

        $fileName = $this->generateFileName($uploadFile["name"]);
        $fullPath = $this->targetDirectory . $fileName;


        if (!move_uploaded_file($uploadFile["tmp_name"], $fullPath)) {
            throw new InvalidArgumentException("An error occurred while uploading the file to the server");
        }

        return $this->publicDirectory . $fileName;
    }

    public function delete(string $filePath) {
        if(empty($filePath)) {
            throw new InvalidArgumentException("The path to delete the file does not exist");
        }

        $fullPath = $_SERVER["DOCUMENT_ROOT"] . $filePath;

        if(file_exists($fullPath) && is_file($fullPath)) {
            unlink($fullPath);
            return;
        }

        return false;
    }

    public function updateImage(array $newUploadFile, ?string $oldImagePath = null) {
        if(empty($newUploadFile) || $newUploadFile["error"] === UPLOAD_ERR_NO_FILE) {
            return;
        }

        if(!empty($oldImagePath)) {
            $this->delete($oldImagePath);
        }

        return $this->upload($newUploadFile);
    }


    public function generateFileName(string $originalName)
    {
        $nameFile = pathinfo($originalName, PATHINFO_FILENAME);
        $extensionFile = "." . pathinfo($originalName, PATHINFO_EXTENSION);

        return $nameFile . "_" . uniqid() . "_" . time() . $extensionFile;
    }

    public function validateMimeType(string $origMimeType)
    {
        if (!in_array($origMimeType, self::ALLOWED_MIME_TYPES)) {
            throw new InvalidArgumentException("Invalid file format for upload");
        }
    }

    public function validateUpload(array $uploadFile)
    {
        if (!isset($uploadFile["error"]) || $uploadFile["error"] !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException("Error upload file");
        }

        if (empty($uploadFile["name"])) {
            throw new InvalidArgumentException("Error! File name cannot be empty");
        }

        if (!is_uploaded_file($uploadFile["tmp_name"])) {
            throw new InvalidArgumentException("File was not uploaded using HTTP POST");
        }
    }

    public function setMimeType(string $targetMimeType) {
        $this->mimeType = $targetMimeType;
    }

    public function getMimeType() {
        return $this->mimeType;
    }














    // public function uploadImage(array $image)
    // {
    //     $arrayMIMEType = ["image/png", "image/jpeg"];
    //     $nameFile = pathinfo($image["name"], PATHINFO_FILENAME);
    //     $extensionFile = "." . pathinfo($image["name"], PATHINFO_EXTENSION);
    //     $typeFile = $image["type"];
    //     $tmpName = $image["tmp_name"];
    //     $targetDirectory = dirname($_SERVER["DOCUMENT_ROOT"]) . "/public/img/posts/";
    //     dd($targetDirectory);
    //     $targetFile = $targetDirectory . $nameFile . "_" . uniqid() . "_" . time() . $extensionFile;
    //     $relativePathImage = "/img/posts/" . basename($targetFile);

    //     if (!in_array($typeFile, $arrayMIMEType)) {
    //             throw new InvalidArgumentException("Недоступный формат файла для загрузки");
    //     }

    //     if (is_uploaded_file($tmpName)) {
    //         if (!move_uploaded_file($tmpName, $targetFile)) {
    //             throw new DomainException("Произошла ошибка при загрузке файла на сервер");
    //         } else {
    //             return $relativePathImage;
    //         }
    //     }
    // }

}
