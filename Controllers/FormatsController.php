<?php

namespace Controllers;

use Core\Database;
use Core\Session;
use Core\Validator;
use Core\ResourceInUseException;

class FormatsController
{
    private Database $db;


    public function __construct()
    {
        $this->db = Database::get();
    }

    public function index($subDir)
    {
        $sql = "SELECT * from mediji ORDER BY id";
        $mediji = $this->db->query($sql)->all();
        $pageTitle = 'Mediji';
        $message = Session::get('message');
        require base_path('views/formats/index.view.php');
    }

    public function show($subDir)
    {
        if (!isset($_GET['id'])) {
            abort();
        }
        
        $sql = "SELECT * from mediji WHERE id = :id";
        $medij = $this->db->query($sql, ['id' => $_GET['id']])->findOrFail();
        $pageTitle = 'Medij';
        
        require base_path('views/formats/show.view.php');
       
    }

    public function edit($subDir)
    {
        if (!isset($_GET['id'])) {
            abort();
        }

        $sql = 'SELECT * from mediji WHERE id = :id';
        $medij = $this->db->query($sql, ['id' => $_GET['id']])->findOrFail();
        $pageTitle = "Uredi medij";
        $errors = Session::get('errors');
        require base_path('views/formats/edit.view.php');
    }

    public function update()
    {
        if (!isset($_POST['id'] ) || !isset($_POST['_method']) || $_POST['_method'] !== 'PATCH') {
            abort();
        }
        
        $rules = [
            'id' => ['required', 'numeric'],
            'tip' => ['required', 'string', 'max:50', 'min:2'],
            'koeficijent' => ['required', 'numeric','max:50']
        ];
        
        $sql = 'SELECT * from mediji WHERE id = :id';
        $medij = $this->db->query($sql, ['id' => $_POST['id']])->findOrFail();
        
        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }
        
        $data = $form->getData();
        $sql = "UPDATE mediji SET tip = :tip, koeficijent = :koeficijent WHERE id = :id";
        $this->db->query($sql, [
            'tip' => $data['tip'],
            'koeficijent' => $data['koeficijent'],
            'id' => $_POST['id']
        ]);
        
        $pageTitle = "Uredi medij";

        Session::flash('message', [
            'type' => 'success',
            'message' => "Uspjesno promijenjen medij"
        ]);

        redirect('videoteka/formats');
       
    }

    public function create($subDir)
    {
        $pageTitle = 'Novi medij';
        $errors = Session::get('errors');
        require base_path('views/formats/create.view.php');
    }

    public function store($subDir)
    {   
        $rules = [
            'tip' => ['required', 'string', 'max:50', 'min:2'],
            'koeficijent' => ['required', 'numeric','max:3']
        ];
        
        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }
        
        $data = $form->getData();
        
        $sql = "SELECT id FROM mediji WHERE tip = :tip";
        $count = $this->db->query($sql, ['tip' => $data['tip']])->find();
        
        if(!empty($count)){
            die("Medij {$data['tip']} vec postoji u nasoj bazi!");
        }
        
        $sql = "INSERT INTO mediji (tip, koeficijent) VALUES (:tip, :koeficijent)";
        $this->db->query($sql, [
            'tip' => $data['tip'],
            'koeficijent' => $data['koeficijent']
        ]);

        Session::flash('message', [
            'type' => 'success',
            'message' => "Uspjesno dodan medij"
        ]);
        
        redirect(substr_replace($subDir, '', 0, 1) . '/formats');
    }

    public function destroy($subDir)
    {
        if (!isset($_POST['id'] )) {
            abort();
        }
        
        $format = $this->db->query("SELECT * from mediji WHERE id = :id", ['id' => $_POST['id']])->findOrFail();

        try {
            $this->db->query("DELETE from mediji WHERE id = :id", ['id' => $_POST['id']]);
        } catch (ResourceInUseException $exception) {
            Session::flash('message', [
                'type' => 'danger',
                'message' => "Ne mozete obrisati medij {$format['tip']} prije nego obrisete sve kopije koje koriste ovaj medij"
            ]);
            goBack();
        }

        Session::flash('message', [
            'type' => 'success',
            'message' => "Uspjesno obrisan medij {$format['tip']}"
        ]);
        redirect(substr_replace($subDir, '', 0, 1) . '/formats');
     
    }
}