<?php

use Core\Database;

$db = Database::get();

$sql = "SELECT * FROM zanrovi ORDER BY id";
$genres = $db->query($sql)->all();


$sql = "SELECT * FROM cjenik ORDER BY id";
$prices = $db->query($sql)->all();

$sql = "SELECT tip FROM mediji ORDER BY id";
$mediji = $db->query($sql)->all();
$mediji_count = $db->query($sql)->ukupno();
(int)$columns = round(12/$mediji_count);

require base_path('views/movies/create.view.php');