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
    'medij_id' => ['required', 'numeric','max:5'],
    'datum_posudbe' => ['required', 'date'],
    'datum_posudbe' => ['date']
];

//TODO: validate the data

$db = Database::get();

$form = new Validator($rules, $_POST);
if ($form->notValid()){
    dd($form->errors());
}

$data = $form->getData();
$sql ="SELECT f.id AS film_id, m.id AS medij_id 
    FROM posudba_kopija pk 
    JOIN kopija k ON k.id = pk.kopija_id 
    JOIN filmovi f ON f.id = k.film_id 
    JOIN mediji m ON m.id = k.medij_id 
    WHERE pk.posudba_id = :posudba_id";
// dd($data['posudba_id']);
$film_medij = $db->query($sql, ['posudba_id' => $data['posudba_id']])->find();
// dd($film_medij['film_id']);
if ($data['film_id'] != $film_medij['film_id'] || $data['medij_id'] != $film_medij['medij_id']) {
    // oslobodi kopiju
    $sql = "UPDATE kopija SET dostupan = 1 WHERE film_id = :film_id AND dostupan = 0 AND medij_id = :medij_id LIMIT 1";
    $db->query($sql, [
        'film_id' => $film_medij['film_id'],
        'medij_id'=> $film_medij['medij_id']
    ]);
    // odaberi novu kopiju
    $sql = "SELECT id FROM kopija WHERE film_id = :film_id   AND dostupan = 1 AND medij_id = :medij_id LIMIT 1;";
    $nova_kopija_id = $db->query($sql, [
        'film_id' => $data['film_id'],
        'medij_id' =>$data['medij_id']
    ])->find();
    if(empty($nova_kopija_id)){
        die("Odabrani film nije dostupan.");
    } else {
        // označi nedustupnom novu kopiju
        $sql = "UPDATE kopija SET dostupan = 0 WHERE film_id = :film_id AND dostupan = 1 AND medij_id = :medij_id LIMIT 1";
        $db->query($sql, [
            'film_id' => $data['film_id'],
            'medij_id' =>$data['medij_id']
        ]);
        $sql = "UPDATE posudba_kopija SET kopija_id = :kopija_id WHERE posudba_id = :posudba_id";
        $db->query($sql, [
            'kopija_id' => $nova_kopija_id['id'],
            'posudba_id' =>$data['posudba_id']
        ]);
    }
    
} 

if ($data['datum_povrata'] === '') {$data['datum_povrata'] = NULL;}
$sql = "UPDATE posudba SET clan_id = :clan_id, datum_posudbe = :datum_posudbe, datum_povrata = :datum_povrata WHERE id = :posudba_id";
        $db->query($sql, [
            'clan_id' => $data['clan_id'],
            'datum_posudbe' => $data['datum_posudbe'],
            'datum_povrata' => $data['datum_povrata'],
            'posudba_id' =>$data['posudba_id']
        ]);
redirect('videoteka/rentals/show?id=' . $data['posudba_id']);
