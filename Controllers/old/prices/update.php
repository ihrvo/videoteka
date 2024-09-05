<?php

use Core\Database;
use Core\Validator;

if (!isset($_POST['id'] ) || !isset($_POST['_method']) || $_POST['_method'] !== 'PATCH') {
    abort();
}


$rules = [
    'id' => ['required', 'numeric'],
    'tip_filma' => ['required', 'string', 'max:50', 'min:2'],
    'cijena' => ['required', 'numeric','max:5'],
    'zakasnina_po_danu' => ['required', 'numeric','max:5']
];

$db = Database::get();
$sql = 'SELECT * from cjenik WHERE id = :id';
$cijena = $db->query($sql, ['id' => $_POST['id']])->findOrFail();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();


$sql = "UPDATE cjenik SET tip_filma = :tip_filma, cijena = :cijena, zakasnina_po_danu = :zakasnina_po_danu WHERE id = :id";
$db->query($sql, [
    'tip_filma' => $data['tip_filma'],
    'cijena' => $data['cijena'],
    'zakasnina_po_danu' => $data['zakasnina_po_danu'],
    'id' => $_POST['id']
]);


$pageTitle = "Uredi cijenu";

redirect('videoteka/prices');
