<?php

use Core\Database;

$db = Database::get();

$sql = "SELECT * FROM zanrovi ORDER BY id";
$genres = $db->query($sql)->all();


$sql = "SELECT * FROM cjenik ORDER BY id";
$prices = $db->query($sql)->all();

$sql = "SELECT
f.id,
f.naslov,
f.godina,
z.ime AS zanr,
c.tip_filma
from
    filmovi f
    JOIN cjenik c ON f.cjenik_id = c.id
    JOIN zanrovi z ON f.zanr_id = z.id
WHERE 
    f.id = :id";
$movie = $db->query($sql, ['id' => $_GET['id']])->findOrFail();

require base_path('views/movies/edit.view.php');