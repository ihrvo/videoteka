<?php

use Core\Database;

$db = Database::get();
$sql = "SELECT * from mediji ORDER BY id";
$mediji = $db->query($sql)->all();


$pageTitle = 'Mediji';

require base_path('views/mediji/index.view.php');