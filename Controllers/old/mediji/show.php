<?php

use Core\Database;

if (!isset($_GET['id'])) {
    abort();
}

$db = Database::get();

$sql = "SELECT * from mediji WHERE id = :id";
$medij = $db->query($sql, ['id' => $_GET['id']])->findOrFail();

$pageTitle = 'Medij';

require base_path('views/mediji/show.view.php');

