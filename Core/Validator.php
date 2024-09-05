<?php

namespace Core;

class Validator
{
    private Database $db;
    private array $data = [];
    private array $errors = [];

    public function __construct(
        private array $rules,
        private array $form
    )
    {
        $this->db = new Database();
        $this->validate();
    }

    public function notValid()
    {
        return !empty($this->errors);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    private function addError(string $key, string $error)
    {
        $this->errors[$key] = $error;
    }

    private function validate()
    {
        foreach ($this->rules as $field => $rules) {
            $userInput = $this->form[$field] ?? '';
            $userInput = trim($userInput);
            $userInput = empty($userInput) ? null : htmlspecialchars($userInput);// sprjecava XSS napad - cross site scripting


            if (!in_array('required', $rules) && is_null($userInput)) {
                continue;
            }

            $this->data[$field] = $userInput;
            
            foreach ($rules as $rule) {

                $additional = null;
                $matchingField = null;

                if (str_contains($rule, ':')) {
                    $pieces = explode(':', $rule);
                    $rule = $pieces[0];
                    $additional = $pieces[1];

                    if (str_contains($additional, ',')) {
                        $temp = explode(',', $additional);
                        $additional = $temp[0];
                        $matchingField = $temp[1];
                    }
                }

                call_user_func([$this, $rule], $userInput, $field, $additional, $matchingField);
            }
        }
    }

    private function exists($userInput, $field, $table, $matchingField)
    {
        $sql = "SELECT COUNT(id) AS count from $table WHERE $matchingField = :val";
        $result = $this->db->query($sql, [
            'val' => $userInput
        ])->find();

        if ($result['count'] === 0)
            $this->addError($field, "Podatak za $field {$userInput} ne postoji u nasoj bazi.");
    }

    private function unique($userInput, $field, $table, $id)
    {
        $sql = "SELECT COUNT(id) AS count from $table WHERE $field = :val";
        $params =  ['val' => $userInput];

        if ($id) {
            $sql .= " AND id != :id";
            $params['id'] = $id;
        }

        $result = $this->db->query($sql, $params)->find();

        if ($result['count'] > 0)
            $this->addError($field, "Podatak za $field {$userInput} vec postoji u nasoj bazi.");
    }

    private function required($userInput, $field)
    {
        if(empty($userInput)){
            $this->addError($field, "Polje $field je obavezno!");
        }
    }

    private function string($userInput, $field)
    {
        if(!preg_match('/^[\pL0-9\s-]+$/u', $userInput)){
            $this->addError($field, "Polje $field moze sadrzavati samo slova i brojeve.");
        }
    }

    private function numeric($userInput, $field)
    {
        if(!is_numeric($userInput)){
            $this->addError($field, "Polje $field mora biti brojcana vrijednost!");
        }
    }

    private function email($userInput, $field)
    {
        if(!filter_var($userInput, FILTER_VALIDATE_EMAIL)){
            $this->addError($field, "Polje $field mora valjana e-mail adresa!");
        }
    }

    private function phone($userInput, $field)
    {
        if(!preg_match('/^(\+\d{3}\s?)(\d{2}\s?)(\d{6,7})$/', $userInput)){
            $this->addError($field, "Polje $field mora biti u formatu +xxx xx xxxxxx");
        } else {
            $this->data[$field] = str_replace(' ', '', $userInput);
        }
    }

    private function max($userInput, $field, $length)
    {
        if(strlen($userInput) > $length){
            $this->addError($field, "Polje $field ne smije biti duze od $length znakova.");
        }
    }

    private function min($userInput, $field, $length)
    {
        if(strlen($userInput) < $length){
            $this->addError($field, "Polje $field ne smije biti krace od $length znakova.");
        }
    }

    private function gt($userInput, $field, $value)
    {
        if(floatval($userInput) < intval($value)){
            $this->addError($field, "Polje $field mora biti brojcana vrijednost veca od $value.");
        }
    }

    private function lt($userInput, $field, $value)
    {
        if(floatval($userInput) > intval($value)){
            $this->addError($field, "Polje $field mora biti brojcana vrijednost manja od $value.");
        }
    }

    private function clanskiBroj($userInput, $field)
    {
        if(!preg_match('/^(CLAN\d{5})$/', $userInput)){
            $this->addError($field, "Polje $field mora biti u formatu CLANxxxxx");
        }
    }

    private function password($userInput, $field)
    {
        // if(!preg_match('/^(CLAN\d{5})$/', $userInput)){
        //     $this->addError($field, "Polje $field mora sadrzavati bar jedan poseban znak i jedno veliko slovo");
        // }
    }

    private function date($userInput, $field)
    {
        $d = \DateTime::createFromFormat('Y-m-d', $userInput);
        if (!$d || $d->format('Y-m-d') !== $userInput) {
            $this->addError($field, "Polje $field mora biti datum u formatu Y-m-d!");
        }
    } 
}