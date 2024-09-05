<?php

use Core\Database;

if (!isset($_GET['id'])) {
    abort();
}

$db = Database::get();

$sql = 'SELECT * from clanovi WHERE id = :id';
$member = $db->query($sql, ['id' => $_GET['id']])->findOrFail();


$pageTitle = "Uredi Clana";

require base_path('views/members/edit.view.php');