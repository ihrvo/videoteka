<?php

use Core\Database;
use Core\Validator;

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    dd('Unsupported method!');
}
    

$rules = [
    'tip' => ['required', 'string', 'max:50', 'min:2'],
    'koeficijent' => ['required', 'numeric','max:3']
];

//TODO: validate the data

$db = Database::get();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();

$sql = "SELECT id FROM mediji WHERE tip = :tip";
$count = $db->query($sql, ['tip' => $data['tip']])->find();

if(!empty($count)){
    die("Medij {$data['tip']} vec postoji u nasoj bazi!");
}


$sql = "INSERT INTO mediji (tip, koeficijent) VALUES (:tip, :koeficijent)";
$db->query($sql, [
    'tip' => $data['tip'],
    'koeficijent' => $data['koeficijent']
]);

redirect('videoteka/mediji');
