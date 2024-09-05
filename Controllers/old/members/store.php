<?php

use Core\Database;
use Core\Validator;

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    dd('Unsupported method!');
}
    

$rules = [
    'ime' => ['required', 'string', 'max:50', 'min:2'],
    'prezime' => ['required', 'string','max:50'],
    'adresa' => ['string'],
    'telefon' => ['phone','max:12'],
    'email' => ['required', 'email', 'exists:clanovi']
];

//TODO: validate the data

$db = Database::get();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();

$sql = "SELECT id FROM clanovi WHERE email = :email";
$count = $db->query($sql, ['email' => $data['email']])->find();

if(!empty($count)){
    die("Korisnik sa emailom {$data['email']} vec postoji u nasoj bazi!");
}

// Genereate next clanski_broj
$sql = "SELECT clanski_broj FROM clanovi ORDER BY id DESC LIMIT 1";
$clanskiBroj = $db->query($sql)->find();
$clanskiBroj = str_replace('CLAN','', $clanskiBroj['clanski_broj']);
$clanskiBroj = intval($clanskiBroj);
$clanskiBroj = 'CLAN' . ++$clanskiBroj;

$sql = "INSERT INTO clanovi (ime, prezime, adresa, telefon, email, clanski_broj) VALUES (:ime, :prezime, :adresa, :telefon, :email, :clanski_broj)";
$db->query($sql, [
    'ime' => $data['ime'],
    'prezime' => $data['prezime'],
    'adresa' => $data['adresa'],
    'telefon' => $data['telefon'],
    'email' => $data['email'],
    'clanski_broj' => $clanskiBroj
]);

redirect('videoteka/members');
