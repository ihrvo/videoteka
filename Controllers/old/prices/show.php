<?php

use Core\Database;

if (!isset($_GET['id'])) {
    abort();
}

$db = Database::get();

$sql = "SELECT * from cjenik WHERE id = :id";
$price = $db->query($sql, ['id' => $_GET['id']])->findOrFail();

$pageTitle = 'Cijena';

require base_path('views/prices/show.view.php');

