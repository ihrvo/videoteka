<?php

use Core\Database;

if (!isset($_POST['id'] ) || !isset($_POST['_method']) || $_POST['_method'] !== 'DELETE') {
    abort();
}

$db = Database::get();


$sql = "DELETE from zanrovi WHERE id = :id";

try {
    $db->query($sql, ['id' => $_POST['id']]);
} catch (\Throwable $th) {
    //throw $th;
}


redirect('videoteka/genres');