<?php

use Core\Database;
use Core\Validator;

if (!isset($_POST['id'] ) || !isset($_POST['_method']) || $_POST['_method'] !== 'PATCH') {
    abort();
}

$rules = [
    'id' => ['required', 'numeric'],
    'ime' => ['required', 'string', 'min:3', 'max:100', 'unique:zanrovi']
];

$db = Database::get();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();

$sql = "UPDATE zanrovi SET ime = ? WHERE id = ?";
$db->query($sql, [$data['ime'], $data['id']]);

redirect('videoteka/genres');
