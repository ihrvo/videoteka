<?php

use Core\Database;
use Core\Validator;

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    dd('Unsupported method!');
}
    
$rules = [
    'naslov' => ['required', 'string', 'min:2', 'max:100'],
    'godina' => ['required', 'numeric', 'min:4', 'max:4'],
    'zanr_id' => ['required', 'numeric'],
    'cjenik_id' => ['required', 'numeric']
];


$db = Database::get();
$form = new Validator($rules, $_POST);
if ($form->notValid()){
    Session::flash('errors', $form->errors());
    goBack();
}

$data = $form->getData();
//  dd($data);

$sql = "INSERT INTO filmovi (naslov, godina, zanr_id, cjenik_id) VALUES (:naslov, :godina, :zanr_id, :cjenik_id)";

if($db->query($sql, [
    'naslov' => $data['naslov'],
    'godina' => $data['godina'],
    'zanr_id' => $data['zanr_id'],
    'cjenik_id' => $data['cjenik_id']
])) {
        $sql = "SELECT id FROM filmovi ORDER BY id DESC LIMIT 1";
        $lastID = $db->query($sql)->find();
        // dd($lastID['id']);
        $sql = "SELECT id, tip FROM mediji ORDER BY id";
        $mediji = $db->query($sql)->all();
        foreach ($mediji as $medij):
            $i = 0;
            if(isset($_POST[$medij['tip']])) {
                $barcode = strtoupper(preg_replace('/[^a-zA-Z]/', '', $data['naslov'] . $medij['tip']));
                // dd($barcode);
                $sql = "INSERT INTO kopija (barcode, dostupan, film_id, medij_id) VALUES (:barcode, :dostupan, :film_id, :medij_id)";
                for($i = 0; $i < (int)$_POST[$medij['tip']]; $i++) {
                    $db->query($sql, [
                        'barcode' => $barcode,
                        'dostupan' => 1,
                        'film_id' => $lastID['id'],
                        'medij_id' => $medij['id']
                    ]);
                }
            }
        endforeach;
}
redirect('videoteka/movies');
