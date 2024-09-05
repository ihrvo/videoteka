<?php

namespace Controllers;

use Core\Database;
use Core\Session;
use Core\Validator;
use Core\ResourceInUseException;

class GenresController
{
    private Database $db;


    public function __construct()
    {
        $this->db = Database::get();
    }


    public function index($subDir)
    {
        $sql = "SELECT * from zanrovi ORDER BY id";
        $genres = $this->db->query($sql)->all();
    
        $pageTitle = 'Zanrovi';
        $message = Session::get('message');    
        require base_path('/views/genres/index.view.php');
    }



    public function show($subDir)
    {
        if (!isset($_GET['id'])) {
            abort();
        }
        
        $sql = 'SELECT * from zanrovi WHERE id = :id';
        
        $genre = $this->db->query($sql, ['id' => $_GET['id']])->findOrFail();
        
        $movies = $this->db->query("SELECT f.*, c.tip_filma FROM filmovi f JOIN cjenik c ON f.cjenik_id = c.id WHERE zanr_id = :id", ['id' => $_GET['id']])->all();
        
        require base_path('views/genres/show.view.php');
    }



    public function edit($subDir)
    {
        if (!isset($_GET['id'])) {
            abort();
        }

        $errors = Session::get('errors');
        
        $genre = $this->db->query('SELECT * FROM zanrovi WHERE id = ?', [$_GET['id']])->findOrFail();
        
        $pageTitle = 'Zanrovi';
        
        require base_path('views/genres/edit.view.php');
    }



    public function update($subDir)
    {
        if (!isset($_POST['id'] )) {
            abort();
        }
        
        $genre = $this->db->query('SELECT * FROM zanrovi WHERE id = ?', [$_POST['id']])->findOrFail();
            
        $postData = [
            "ime" => $_POST['ime'],
        ];
        
        $rules = [
            'ime' => ['required', 'string', 'max:100'],
        ];
        
        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }
        
        $data = $form->getData();
        
        $sql = "UPDATE zanrovi SET ime = ? WHERE id = ?";
        $this->db->query($sql, [$data['ime'], $genre['id']]);

        Session::flash('message', [
            'type' => 'success',
            'message' => "Žanr uspješno spremljen"
        ]);
        
        redirect(substr_replace($subDir, '', 0, 1) . '/genres');
    }


    public function create($subDir)
    {
        $pageTitle = 'Zanrovi';
        $errors = Session::get('errors');
        Session::flash('message', [
            'type' => 'success',
            'message' => "Žanr uspješno dodan"
        ]);
        require base_path('views/genres/create.view.php');
    }


    public function store($subDir)
    {        
        $postData = [
            'ime' => $_POST['ime'] ?? null
        ];
        
        $rules = [
            'ime' => ['required', 'string', 'max:100', 'unique:zanrovi'],
        ];
        
        $form = new Validator($rules, $postData);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }
        
        $data = $form->getData();
        
        $sql = "INSERT INTO zanrovi (ime) VALUES (:ime)";
        $this->db->query($sql, ['ime' => $data['ime']]);
        
        redirect(substr_replace($subDir, '', 0, 1) . '/genres');
    }



    public function destroy($subDir)
    {
        if (!isset($_POST['id'])) {
            abort();
        }

        $genre = $this->db->query('SELECT * FROM zanrovi WHERE id = ?', [$_POST['id']])->findOrFail();

        try {
            $this->db->query('DELETE FROM zanrovi WHERE id = ?', [$genre['id']]);
        } catch (ResourceInUseException $e) {
            Session::flash('message', [
                'type' => 'danger',
                'message' => "Ne mozete obrisati {$genre['ime']} prije nego obrisete sve filmove koji pripadaju žanru."
            ]);
            goBack();
        }

        Session::flash('message', [
            'type' => 'success',
            'message' => "Žanr uspješno obrisan"
        ]);
        redirect(substr_replace($subDir, '', 0, 1) . '/genres');
    }
}