<?php

use Core\Database;
use Core\Validator;

if (!isset($_POST['id'] ) || !isset($_POST['_method']) || $_POST['_method'] !== 'PATCH') {
    abort();
}
$rules = [
    'naslov' => ['required', 'string', 'min:2', 'max:100'],
    'godina' => ['required', 'numeric', 'min:4', 'max:4'],
    'zanr_id' => ['required', 'numeric'],
    'cjenik_id' => ['required', 'numeric']
];


$db = Database::get();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();
// dd($data);

$sql = "UPDATE filmovi SET naslov = :naslov, godina = :godina, zanr_id = :zanr_id, cjenik_id = :cjenik_id WHERE id = :id";
$db->query($sql, [
    'naslov' => $data['naslov'],
    'godina' => $data['godina'],
    'zanr_id' => $data['zanr_id'],
    'cjenik_id' => $data['cjenik_id'],
    'id' => $_POST['id']
]);

redirect('videoteka/movies');
