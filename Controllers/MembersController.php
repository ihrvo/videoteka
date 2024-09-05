<?php

namespace Controllers;

use Core\Database;
use Core\Session;
use Core\Validator;
use Core\ResourceInUseException;

class MembersController
{
    private Database $db;


    public function __construct()
    {
        $this->db = Database::get();
    }


    public function index($subDir)
    {
        $sql = "SELECT * from clanovi ORDER BY id";
        $members = $this->db->query($sql)->all();
        
        $pageTitle = 'Clanovi';
        $message = Session::get('message');
        require base_path('views/members/index.view.php');
    }



    public function show($subDir)
    {
        if (!isset($_GET['id'])) {
            abort();
        }
        
        $sql = "SELECT * from clanovi WHERE id = :id";
        $member = $this->db->query($sql, ['id' => $_GET['id']])->findOrFail();
        
        $pageTitle = 'Clan';
        $message = Session::get('message');
        require base_path('views/members/show.view.php');
    }



    public function edit($subDir)
    {
        if (!isset($_GET['id'])) {
            abort();
        }
        
        $sql = "SELECT * from clanovi WHERE id = :id";
        $member = $this->db->query($sql, ['id' => $_GET['id']])->findOrFail();
        
        $pageTitle = "Uredi Clana";
        
        $errors = Session::get('errors');
        
        require base_path('views/members/edit.view.php');
    }



    public function update($subDir)
    {
        if (!isset($_POST['id'])) {
            abort();
        }

        $rules = [
            'id' => ['required', 'numeric'],
            'ime' => ['required', 'string', 'max:50', 'min:2'],
            'prezime' => ['required', 'string','max:50'],
            'adresa' => ['string','max:100'],
            'telefon' => ['phone','max:15'],
            'email' => ['required', 'email','max:50', 'unique:clanovi,' . $_POST['id']],
            'clanski_broj' => ['required', 'string', 'max:14', 'clanskiBroj', 'unique:clanovi,' . $_POST['id']],
        ];
        
        $sql = 'SELECT * from clanovi WHERE id = :id';
        $member = $this->db->query($sql, ['id' => $_POST['id']])->findOrFail();
        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }
        
        $data = $form->getData();
        
        
        $sql = "UPDATE clanovi SET ime = :ime, prezime = :prezime, adresa = :adresa, telefon = :telefon, email = :email, clanski_broj = :clanski_broj WHERE id = :id";
        $this->db->query($sql, [
            'ime' => $data['ime'],
            'prezime' => $data['prezime'],
            'adresa' => $data['adresa'],
            'telefon' => $data['telefon'],
            'email' => $data['email'],
            'clanski_broj' => $data['clanski_broj'],
            'id' => $_POST['id']
        ]);
        
        $pageTitle = "Edit Member";
        
        Session::flash('message', [
            'type' => 'success',
            'message' => "Uspjesno uredjeni podaci o clanu '{$data['ime']} {$data['prezime']}'."
        ]);
        $errors = Session::get('errors');
        redirect(substr_replace($subDir, '', 0, 1) . '/members');
    }


    public function create($subDir)
    {
        $pageTitle = 'Clanovi';

        $errors = Session::get('errors');
        $message = Session::get('message');
        require base_path('views/members/create.view.php');
    }


    public function store($subDir)
    {
        $rules = [
            'ime' => ['required', 'string', 'max:50', 'min:2'],
            'prezime' => ['required', 'string','max:50'],
            'adresa' => ['string','max:100'],
            'telefon' => ['phone','max:15'],
            'email' => ['required', 'email', 'max:50', 'unique:clanovi'],
        ];
        
        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }
        
        $data = $form->getData();
        
        $sql = "SELECT clanski_broj FROM clanovi ORDER BY id DESC LIMIT 1";
        $clanskiBroj = $this->db->query($sql)->find();
        $clanskiBroj = str_replace('CLAN','', $clanskiBroj['clanski_broj']);
        $clanskiBroj = intval($clanskiBroj);
        $clanskiBroj = 'CLAN' . ++$clanskiBroj;
        
        $sql = "INSERT INTO clanovi (ime, prezime, adresa, telefon, email, clanski_broj) VALUES (:ime, :prezime, :adresa, :telefon, :email, :clanski_broj)";
        $this->db->query($sql, [
            'ime' => $data['ime'],
            'prezime' => $data['prezime'],
            'adresa' => $data['adresa'],
            'telefon' => $data['telefon'],
            'email' => $data['email'],
            'clanski_broj' => $clanskiBroj
        ]);
        
        Session::flash('message', [
            'type' => 'success',
            'message' => "Uspjesno kreiran clan '{$data['ime']} {$data['prezime']}'."
        ]);
        
        redirect(substr_replace($subDir, '', 0, 1) . '/members');
    }



    public function destroy($subDir)
    {
        if (!isset($_POST['id'] )) {
            abort();
        }
        
        $sql = 'SELECT * from clanovi WHERE id = :id';
        $member = $this->db->query($sql, ['id' => $_POST['id']])->findOrFail();
        
        $sql = "DELETE from clanovi WHERE id = :id";
        
        try {
            $this->db->query($sql, ['id' => $_POST['id']]);
        } catch (ResourceInUseException $e) {
            Session::flash('message', [
                'type' => 'danger',
                'message' => "Ne mozete obrisati clana {$member['ime']} {$member['prezime']} prije nego obrisete njegove posudbe."
            ]);
            goBack();
        }
        
        Session::flash('message', [
            'type' => 'success',
            'message' => "Uspjesno obrisan clan '{$member['ime']} {$member['prezime']}'."
        ]);
        
        redirect(substr_replace($subDir, '', 0, 1) . '/members');
    }
}