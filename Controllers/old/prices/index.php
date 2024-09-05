<?php

use Core\Database;

$db = Database::get();
$sql = "SELECT * from cjenik ORDER BY id";
$prices = $db->query($sql)->all();


$pageTitle = 'Cjenik';

require base_path('views/prices/index.view.php');