<?php

use Core\Database;

if (!isset($_GET['id'])) {
    abort();
}

$db = Database::get();
$sql = "SELECT 
    p.id, 
    p.datum_posudbe,
    p.datum_povrata,
    CONCAT(c.clanski_broj, ' ' , c.ime, ' ', c.prezime) AS clan, 
    f.naslov, 
    m.tip,
    CONCAT(cj.cijena, ' € - ', cj.tip_filma) AS cijena_tip_filma,
    cj.zakasnina_po_danu,
    cj.cijena
    FROM posudba p
    JOIN posudba_kopija pk ON p.id = pk.posudba_id
    JOIN kopija k ON k.id = pk.kopija_id
    JOIN filmovi f ON f.id = k.film_id
    JOIN clanovi c ON c.id = p.clan_id
    JOIN cjenik cj ON cj.id = f.cjenik_id
    JOIN mediji m ON m.id = k.medij_id
    WHERE p.id = :id
    GROUP BY p.id, p.datum_posudbe, clan, f.naslov, m.tip, cijena_tip_filma, zakasnina_po_danu, cijena
    ORDER BY p.datum_posudbe DESC";
$posudbe = $db->query($sql, ['id' => $_GET['id']])->findOrFail();

$danas = new DateTime(date('Y-m-d'));
$datum_posudbe = new DateTime($posudbe['datum_posudbe']);

$dani_posudbe = $datum_posudbe->diff($danas)->days;

$vratiti_do_datuma = $datum_posudbe->modify('+1 day');
if ($danas > $vratiti_do_datuma) {
    $dani_kasnjenja = $vratiti_do_datuma->diff($danas)->days;
}
else {
    $dani_kasnjenja = 0;
}


$zakasnina = $posudbe['zakasnina_po_danu'] * $dani_kasnjenja;
$dugovanje = $zakasnina + $posudbe['cijena'];

$pageTitle = 'Posudba';

require base_path('views/rentals/show.view.php');