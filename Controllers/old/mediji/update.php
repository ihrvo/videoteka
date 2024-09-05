<?php

use Core\Database;
use Core\Validator;

if (!isset($_POST['id'] ) || !isset($_POST['_method']) || $_POST['_method'] !== 'PATCH') {
    abort();
}

$rules = [
    'id' => ['required', 'numeric'],
    'tip' => ['required', 'string', 'max:50', 'min:2'],
    'koeficijent' => ['required', 'numeric','max:50']
];

$db = Database::get();
$sql = 'SELECT * from mediji WHERE id = :id';
$medij = $db->query($sql, ['id' => $_POST['id']])->findOrFail();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();


$sql = "UPDATE mediji SET tip = :tip, koeficijent = :koeficijent WHERE id = :id";
$db->query($sql, [
    'tip' => $data['tip'],
    'koeficijent' => $data['koeficijent'],
    'id' => $_POST['id']
]);


$pageTitle = "Uredi medij";

redirect('videoteka/mediji');
