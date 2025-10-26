<?php 

namespace App\Services;

class ValidationService{
    private array $errors = [];
    private bool $passed = false;
    private string $currentFiled = "";
    private mixed $currentValue = "";

    public function validate(array $data, string $action) {
        // dd($data);

        switch($action) {
            case "registration": {
                $this->validateRegistration($data);
                break;
            }
            case "auth": {
                $this->validateAuth($data);
                break;
            }
            case "validateCreatePost": {
                $this->validateCreatePost($data);
                break;
            }
        }

        // dd($data);
        // dd($this->errors);

        $this->errors = $this->getActualErrors($data, $this->errors());
        // dd($this->errors);
        if(empty($this->errors())) {
            $this->passed = true;
        }

        return $this;    
    }

    public function validateRegistration($data) {
        $this->field("email", $data["email"] ?? "")->required()->validateEmail();

        $this->field("password", $data["password"] ?? "")->required()->minLength(2);

        $this->field("username", $data["username"] ?? "")->required()->minLength(2)->maxLength(30);
    }

    public function validateAuth($data) {
        $this->field("email", $data["email"] ?? "")->required()->validateEmail();

        $this->field("password", $data["password"] ?? "")->required()->minLength(2);
    }

    public function validateCreatePost($data) {
        $this->field("title", $data["title"] ?? "")->required()->minLength(7)->maxLength(50);

        $this->field("description", $data["description"] ?? "")->required()->minLength(10)->maxLength(100);

        $this->field("image_post", $data["image_post"] ?? [])->requiredImage()->typeImage();
    }

    public function validateEditPost($data) {
        $this->field("title", $data["title"] ?? "")->minLength(7)->maxLength(50);
        $this->field("description", $data["description"] ?? "")->minLength(10)->maxLength(100);
        $this->field("image_post", $data["image_post"] ?? "")->typeImage();
    }

    public function getActualErrors($data, $errors) {
        return array_intersect_key($errors, $data);
    }

    public function field(string $fieldName, mixed $value) {
        $this->currentFiled = $fieldName;
        $this->currentValue = $value;

        return $this;
    }

    public function required(){
        if(empty($this->currentValue)) {
            $this->addError("Заполните поле");
        }

        return $this;
    }

    public function requiredImage() {
        if(isset($this->currentValue) && empty($this->currentValue["tmp_name"])) {
            $this->addError("Выберите картинку");
        }

        return $this;
    }

    public function typeImage(){
        $type = ["image/png", "image/jpeg"];
        
        if(!in_array($this->currentValue["type"], $type)) {
            $this->addError("Неверный формат файла.<br>Для загрузки доступны только \"png\", \"jpeg\"");
        }

        return $this;
    }

    public function validateEmail(){
        if(!filter_var($this->currentValue, FILTER_VALIDATE_EMAIL)) {
            $this->addError("Некорректный Email адрес");
        }

        return $this;
    }

    public function minLength($length){
        if(strlen($this->currentValue) <= $length) {
            $this->addError("Значение поля не должно быть меньше $length символов");
        }

        return $this;
    }

    public function maxLength($length) {
        if(strlen($this->currentValue) >= $length) {
            $this->addError("Значение поля не должно быть больше $length символов");
        }

        return $this;
    }

    public function errors(){
        return $this->errors;
    }

    public function passed(){
        return $this->passed;
    }

    public function addError($error) {
        if(!isset($this->errors[$this->currentFiled])) {
            $this->errors[$this->currentFiled] = [];
        }
        $this->errors[$this->currentFiled][] = $error;
    }

    public function addErrorException($error, $field = "errException") {
        $this->errors[$field][] = $error;
    }

    public function getFirstError($fieldName) {
        return $this->errors[$fieldName][0];
    }
}

?>