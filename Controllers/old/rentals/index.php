<?php

use Core\Database;

$db = Database::get();
$sql = "SELECT 
    p.id, 
    p.datum_posudbe,
    p.datum_povrata,
    CONCAT(c.clanski_broj, ' ' , c.ime, ' ', c.prezime) AS clan, 
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
    GROUP BY p.id, p.datum_posudbe, clan, f.naslov, m.tip, cijena_tip_filma
    ORDER BY p.datum_posudbe DESC";
$posudbe = $db->query($sql)->all();



$pageTitle = 'Posudbe';

require base_path('views/rentals/index.view.php');