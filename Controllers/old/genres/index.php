<?php

use Core\Database;

$db = Database::get();

$sql = "SELECT * from zanrovi ORDER BY id";
$genres = $db->query($sql)->all();

$pageTitle = 'Zanrovi';

require '../views/genres/index.view.php';