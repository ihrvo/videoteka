<?php

use Core\Database;
use Core\Validator;

if (!isset($_POST['posudba_id'] ) || !isset($_POST['_method']) || $_POST['_method'] !== 'DELETE') {
    abort();
}

$rules = [
    'posudba_id' => ['required', 'numeric'],
];

$db = Database::get();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();

$kopija_id = $db->query('SELECT kopija_id from posudba_kopija WHERE posudba_id = :posudba_id', ['posudba_id' => $_POST['posudba_id']])->findOrFail();

// dd($kopija_id['kopija_id']);

$sql = "UPDATE kopija SET dostupan = 1 WHERE id = ?";
if($db->query($sql, [$kopija_id['kopija_id']])) {
    $db->query('UPDATE posudba SET datum_povrata = ? WHERE id = ?', [date('Y-m-d'), $_POST['posudba_id']]);
    $db->query('DELETE from posudba_kopija WHERE posudba_id = ? AND kopija_id = ?', [$_POST['posudba_id'], $kopija_id['kopija_id']]);
}

redirect('videoteka/rentals');