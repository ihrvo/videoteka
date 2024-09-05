<?php

namespace Controllers;

use Core\Database;
use Core\Session;
use Core\Validator;
use Core\ResourceInUseException;

class PricesController
{
    private Database $db;


    public function __construct()
    {
        $this->db = Database::get();
    }

    public function index($subDir)
    {
        $sql = "SELECT * from cjenik ORDER BY id";
        $prices = $this->db->query($sql)->all();
        $pageTitle = 'Cjenik';
        $message = Session::get('message');
        require base_path('views/prices/index.view.php');
    }

    public function show($subDir)
    {
        $sql = "SELECT * from cjenik WHERE id = :id";
        $price = $this->db->query($sql, ['id' => $_GET['id']])->findOrFail();
        $pageTitle = 'Cijena';
        
        require base_path('views/prices/show.view.php');
    }

    public function edit($subDir)
    {
        $sql = 'SELECT * from cjenik WHERE id = :id';
        $price = $this->db->query($sql, ['id' => $_GET['id']])->findOrFail();
        $pageTitle = "Uredi cijenu";
        $errors = Session::get('errors');
        require base_path('views/prices/edit.view.php');
    }

    public function update($subDir)
    {   
        if (!isset($_POST['id'] )) {
            abort();
        }
        
        
        $rules = [
            'id' => ['required', 'numeric'],
            'tip_filma' => ['required', 'string', 'max:50', 'min:2'],
            'cijena' => ['required', 'numeric','max:5'],
            'zakasnina_po_danu' => ['required', 'numeric','max:5']
        ];
        
        $sql = 'SELECT * from cjenik WHERE id = :id';
        $cijena = $this->db->query($sql, ['id' => $_POST['id']])->findOrFail();
        
        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }

        $data = $form->getData();
        
        $sql = "UPDATE cjenik SET tip_filma = :tip_filma, cijena = :cijena, zakasnina_po_danu = :zakasnina_po_danu WHERE id = :id";
        $this->db->query($sql, [
            'tip_filma' => $data['tip_filma'],
            'cijena' => $data['cijena'],
            'zakasnina_po_danu' => $data['zakasnina_po_danu'],
            'id' => $_POST['id']
        ]);
        
        $pageTitle = "Uredi cijenu";
        Session::flash('message', [
            'type' => 'success',
            'message' => "Cijena uspješno promijenjena"
        ]);
        redirect(substr_replace($subDir, '', 0, 1) . '/prices');
    }

    public function create($subDir)
    {
        $pageTitle = 'Nova cijena';
        $errors = Session::get('errors');
        require base_path('views/prices/create.view.php'); 
    }

    public function store($subDir)
    {
        $rules = [
            'tip_filma' => ['required', 'string', 'max:50', 'min:2'],
            'cijena' => ['required', 'numeric','max:5'],
            'zakasnina_po_danu' => ['required', 'numeric','max:5']
        ];
        
        //TODO: validate the data
        
        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }
        
        $data = $form->getData();
        
        $sql = "SELECT id FROM cjenik WHERE tip_filma = :tip_filma";
        $count = $this->db->query($sql, ['tip_filma' => $data['tip_filma']])->find();
        
        if(!empty($count)){
            die("Tip filma {$data['tip_filma']} vec postoji u nasoj bazi!");
        }
        
        $sql = "INSERT INTO cjenik (tip_filma, cijena, zakasnina_po_danu) VALUES (:tip_filma, :cijena, :zakasnina_po_danu)";
        $this->db->query($sql, [
            'tip_filma' => $data['tip_filma'],
            'cijena' => $data['cijena'],
            'zakasnina_po_danu' => $data['zakasnina_po_danu']
        ]);

        Session::flash('message', [
            'type' => 'success',
            'message' => "Cijena uspješno kreirana"
        ]);
        
        redirect(substr_replace($subDir, '', 0, 1) . '/prices');        
    }

    public function destroy($subDir)
    {
        if (!isset($_POST['id'] )) {
            abort();
        }
        
        $sql = 'SELECT * from cjenik WHERE id = :id';
        $medij = $this->db->query($sql, ['id' => $_POST['id']])->findOrFail();
        
        $sql = "DELETE from cjenik WHERE id = :id";
        
        try {
            $this->db->query($sql, ['id' => $_POST['id']]);
        } catch (\Throwable $th) {
            //throw $th;
        }
        Session::flash('message', [
            'type' => 'success',
            'message' => "Cijena uspješno obrisana"
        ]);
        redirect(substr_replace($subDir, '', 0, 1) . '/prices');
     
    }
}