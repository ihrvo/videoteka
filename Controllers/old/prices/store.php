<?php

use Core\Database;
use Core\Validator;

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    dd('Unsupported method!');
}
    

$rules = [
    'tip_filma' => ['required', 'string', 'max:50', 'min:2'],
    'cijena' => ['required', 'numeric','max:5'],
    'zakasnina_po_danu' => ['required', 'numeric','max:5']
];

//TODO: validate the data

$db = Database::get();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();

$sql = "SELECT id FROM cjenik WHERE tip_filma = :tip_filma";
$count = $db->query($sql, ['tip_filma' => $data['tip_filma']])->find();

if(!empty($count)){
    die("Tip filma {$data['tip_filma']} vec postoji u nasoj bazi!");
}


$sql = "INSERT INTO cjenik (tip_filma, cijena, zakasnina_po_danu) VALUES (:tip_filma, :cijena, :zakasnina_po_danu)";
$db->query($sql, [
    'tip_filma' => $data['tip_filma'],
    'cijena' => $data['cijena'],
    'zakasnina_po_danu' => $data['zakasnina_po_danu']
]);

redirect('videoteka/prices');
