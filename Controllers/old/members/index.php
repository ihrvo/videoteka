<?php

use Core\Database;

$db = Database::get();
$sql = "SELECT * from clanovi ORDER BY id";
$members = $db->query($sql)->all();


$pageTitle = 'Clanovi';

require base_path('views/members/index.view.php');