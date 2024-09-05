<?php

use Core\Database;
use Core\Validator;

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    dd('Unsupported method!');
}
    

$rules = [
    'ime' => ['required', 'string', 'min:3', 'max:100', 'exists:zanrovi']
];
// dd($_POST);

$db = Database::get();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();

$sql = "INSERT INTO zanrovi (ime) VALUES (:ime)";

$db->query($sql, ['ime' => $data['ime']]);

redirect('videoteka/genres');
