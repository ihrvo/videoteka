<?php

use Core\Database;

$db = Database::get();

// $sql = "SELECT 
//     p.id, 
//     p.datum_posudbe, 
//     CONCAT(c.clanski_broj, ' ' , c.ime, ' ', c.prezime) AS clan, 
//     f.naslov, 
//     m.tip,
//     CONCAT(cj.cijena, ' € - ', cj.tip_filma) AS cijena_tip_filma
//     FROM posudba p
//     JOIN posudba_kopija pk ON p.id = pk.posudba_id
//     JOIN kopija k ON k.id = pk.kopija_id
//     JOIN filmovi f ON f.id = k.film_id
//     JOIN clanovi c ON c.id = p.clan_id
//     JOIN cjenik cj ON cj.id = f.cjenik_id
//     JOIN mediji m ON m.id = k.medij_id
//     WHERE p.datum_povrata IS NULL
//     GROUP BY p.id, p.datum_posudbe, clan, f.naslov, m.tip, cijena_tip_filma
//     ORDER BY p.datum_posudbe DESC";

$sql = "SELECT 
        p.id AS pid,
        k.id AS kid,
        p.datum_posudbe, 
        CONCAT(c.clanski_broj, ' - ' , c.ime, ' ', c.prezime) AS clan, 
        f.naslov, 
        m.tip,
        CONCAT(cj.cijena, ' € - ', cj.tip_filma) AS cijena_tip_filma
        FROM posudba p
        JOIN posudba_kopija pk ON p.id = pk.posudba_id
        JOIN kopija k ON k.id = pk.kopija_id
        JOIN filmovi f ON f.id = k.film_id
        JOIN clanovi c ON c.id = p.clan_id
        JOIN cjenik cj ON cj.id = f.cjenik_id
        JOIN mediji m ON m.id = k.medij_id
        WHERE p.datum_povrata IS NULL
        ORDER BY p.id DESC";

$rentals = $db->query($sql)->all();

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

$pageTitle = 'Dashboard';

require base_path('views/dashboard/index.view.php');
