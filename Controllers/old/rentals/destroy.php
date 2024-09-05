<?php

use Core\Database;
// use Core\Session;
// use Core\ResourceInUseException;

if ((!isset($_POST['pid']) && !isset($_POST['kid'])) || !isset($_POST['_method']) || $_POST['_method'] !== 'DELETE') {
    abort();
}

$db = Database::get();

$rental = $db->query('SELECT * from posudba WHERE id = :id', ['id' => $_POST['pid']])->findOrFail();
$copy   = $db->query("SELECT * from kopija WHERE id = :id", ['id' => $_POST['kid']])->findOrFail();

$rentals = $db->query('SELECT posudba_id from posudba_kopija WHERE posudba_id = :posudba_id', ['posudba_id' => $_POST['pid']])->all();
// dd(count($rentals));

try {
    $db->connection()->beginTransaction(); 

    if (count($rentals) == 1) {
        // samo jedna kopija je u posudbi, oznaci posudbu kao vraceno
        $db->query("DELETE from posudba_kopija WHERE posudba_id = :pid AND kopija_id = :kid", [
            'pid' => $rental['id'],
            'kid' => $copy['id'],
        ]);
    
        $db->query('UPDATE posudba SET datum_povrata = ? WHERE id = ?', [
            date('Y-m-d'), $_POST['posudba_id']
        ]);
    }else{
        // posudba ima jos ne vracenih koopija , samo osvjezi updated_at
        $db->query("UPDATE posudba SET updated_at = :d WHERE id = :pid", [
            'pid' => $rental['id'],
            'd' => date("Y-m-d H:i:s")
        ]);
    }

    $db->query("UPDATE kopija SET dostupan = 1 WHERE id = :kid", ['kid' => $copy['id']]);
} catch (PDOException $e) {
    $db->connection()->rollBack();
    // Session::flash('message', [
    //     'type' => 'danger',
    //     'message' => 'Something wrong'
    // ]);
    goBack();
}

$db->connection()->commit();

// Session::flash('message', [
//     'type' => 'success',
//     'message' => "Uspjesno vracena kopija {$copy['id']}"
// ]);
goBack();