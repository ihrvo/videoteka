<?php

use Core\Database;

if (!isset($_GET['id'])) {
    abort();
}

$db = Database::get();

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

$pageTitle = 'Film';

require base_path('views/movies/show.view.php');

