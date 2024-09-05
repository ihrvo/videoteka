<?php

use Core\Database;

if (!isset($_GET['id'])) {
    abort();
}

$db = Database::get();

$sql = 'SELECT * from cjenik WHERE id = :id';
$price = $db->query($sql, ['id' => $_GET['id']])->findOrFail();


$pageTitle = "Uredi cijenu";

require base_path('views/prices/edit.view.php');