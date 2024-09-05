<?php

use Core\Database;

$db = Database::get();

$sql = "SELECT id,godina, naslov
        FROM filmovi 
        ORDER BY godina";
$movies = $db->query($sql)->all();

$sql = "SELECT id, tip
        FROM mediji 
        ORDER BY id";
$mediji = $db->query($sql)->all();

$sql = "SELECT id, clanski_broj, ime, prezime
        FROM clanovi 
        ORDER BY clanski_broj";
$clanovi = $db->query($sql)->all();


$sql = "SELECT * FROM cjenik ORDER BY id";
$prices = $db->query($sql)->all();

$pageTitle = 'Nova posudba';
require base_path('views/rentals/create.view.php');