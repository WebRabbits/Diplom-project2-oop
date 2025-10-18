<?php 

namespace App\Services;

class ValidationService{
    private array $errors = [];
    private bool $passed = false;
    private string $currentFiled = "";
    private string $currentValue = "";

    public function validate(array $data) {
        $this->field("email", $data["email"] ?? "")->required()->validateEmail();

        $this->field("password", $data["password"] ?? "")->required()->minLength(2);

        $this->field("username", $data["username"] ?? "")->required()->minLength(2)->maxLength(30);

        // dd($data);
        // dd($this->errors);

        $this->errors = $this->getActualErrors($data, $this->errors());
        // dd($this->errors);
        if(empty($this->errors())) {
            $this->passed = true;
        }

        return $this;    
    }

    public function getActualErrors($data, $errors) {
        return array_intersect_key($errors, $data);
    }

    public function field(string $fieldName, string $value) {
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