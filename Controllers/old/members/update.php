<?php

use Core\Database;
use Core\Validator;

if (!isset($_POST['id'] ) || !isset($_POST['_method']) || $_POST['_method'] !== 'PATCH') {
    abort();
}

$rules = [
    'id' => ['required', 'numeric'],
    'ime' => ['required', 'string', 'max:50', 'min:2'],
    'prezime' => ['required', 'string','max:50'],
    'adresa' => ['string'],
    'telefon' => ['phone','max:12'],
    'email' => ['required', 'email', 'unique:clanovi'],
    'clanski_broj' => ['required', 'string', 'unique:clanovi','max:14'],
];

$db = Database::get();
$sql = 'SELECT * from clanovi WHERE id = :id';
$member = $db->query($sql, ['id' => $_POST['id']])->findOrFail();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();


$sql = "UPDATE clanovi SET ime = :ime, prezime = :prezime, adresa = :adresa, telefon = :telefon, email = :email, clanski_broj = :clanski_broj WHERE id = :id";
$db->query($sql, [
    'ime' => $data['ime'],
    'prezime' => $data['prezime'],
    'adresa' => $data['adresa'],
    'telefon' => $data['telefon'],
    'email' => $data['email'],
    'clanski_broj' => $data['clanski_broj'],
    'id' => $_POST['id']
]);


$pageTitle = "Edit Member";

redirect('videoteka/members');
