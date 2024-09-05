<?php

use Core\Database;

if (!isset($_GET['id'])) {
    abort();
}

$db = Database::get();

$sql = 'SELECT z.*, f.id AS film_id, f.naslov, f.godina, c.tip_filma 
        FROM zanrovi z 
        JOIN filmovi f ON z.id=f.zanr_id 
        JOIN cjenik c ON f.cjenik_id = c.id 
        WHERE z.id = :id';

$genre = $db->query($sql, ['id' => $_GET['id']])->all();

require base_path('views/genres/show.view.php'); 
