<?php

use Core\Database;
use Core\Validator;

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    dd('Unsupported method!');
}
    
$datum = date('Y-m-d');
// dd($datum);
$rules = [
    'clan_id' => ['required', 'numeric','max:5'],
    'film_id' => ['required', 'numeric','max:5'],
    'medij_id' => ['required', 'numeric','max:5']
];

//TODO: validate the data

$db = Database::get();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();

$sql = "SELECT k.id, k.film_id, f.naslov
        FROM kopija k
        JOIN filmovi f ON f.id = k.film_id
        WHERE film_id = :film_id AND dostupan = 1 AND medij_id= :medij_id";
$count = $db->query($sql, ['film_id' => $data['film_id'], 'medij_id' => $data['medij_id']])->find();

if(empty($count)){
    die("Odabrani film nije dostupan.");
}
else {
    $sql = "UPDATE kopija SET dostupan = 0 WHERE film_id = :film_id AND dostupan = 1 AND medij_id = :medij_id LIMIT 1";
    $db->query($sql, [
        'film_id' => $data['film_id'],
        'medij_id' =>$data['medij_id']
    ]);
}


$sql = "INSERT INTO posudba (datum_posudbe, clan_id) VALUES (:datum_posudbe, :clan_id)";
if($db->query($sql, [
    'datum_posudbe' => $datum,
    'clan_id' => $data['clan_id']
]))
{

$sql = "SELECT id FROM posudba ORDER BY id DESC LIMIT 1";
$lastID = $db->query($sql)->find();
// dd($lastID['id']);
$sql = "INSERT INTO posudba_kopija (posudba_id, kopija_id) VALUES (:posudba_id, :kopija_id)";
$db->query($sql, [
    'posudba_id' => $lastID['id'],
    'kopija_id' => $count['id']
]);
}
redirect('videoteka/rentals');
