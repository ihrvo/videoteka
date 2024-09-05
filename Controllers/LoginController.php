<?php

namespace Controllers;

use Core\Database;
use Core\Session;
use Core\Validator;

class LoginController
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::get();
    }

    public function create($subDir)
    {
        $pageTitle = 'Login';
        $errors = Session::get('errors');
        $message = Session::get('message'); 
        $loggedIn = Session::has('user');
        require_once base_path('views/login/create.view.php');
    }

    public function store($subDir)
    {
        // validirati podatke iz forme
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'password', 'min:3', 'max:255']
        ];
        

        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }
        
        $data = $form->getData();
        
        // provjeriti da li postoji user sa datim emailom u bazi
        $user = $this->db->query("SELECT * FROM clanovi WHERE email = ?", [$data['email']])->all();
        // $user['pass'] = password_hash($data['password'], PASSWORD_BCRYPT);
        // dd($user[0]['password']);
        if ($user) {
            // if (password_hash($data['password'], PASSWORD_BCRYPT) === $user['password']) {
            if (password_verify($data['password'], $user[0]['password'])) {
                $this->login($user);
                redirect(substr_replace($subDir, '', 0, 1) . '/dashboard');
            } else {
                // vratiti gresku da je neispravna lozinka
                Session::flash('message', [
                    'type' => 'danger',
                    'message' => "Neispravna lozinka"
                ]);
                // vratiti na login ponovno
                redirect(substr_replace($subDir, '', 0, 1) . '/login');
            }
        } else {
            // vratiti gresku da korisnik ne postoji
            Session::flash('message', [
                'type' => 'danger',
                'message' => "Korisnik ne postoji"
            ]);
            // vratiti na login ponovno
            redirect(substr_replace($subDir, '', 0, 1) . '/login');
        }

        $this->login($data);

        redirect(substr_replace($subDir, '', 0, 1) . '/dashboard');
    }

    public function login($user)
    {
        Session::put('user', [
            'ime' => $user[0]['ime'],
            'prezime' => $user[0]['prezime'],
            'email' => $user[0]['email'],
        ]);

        session_regenerate_id();
    }

    public function logout($subDir)
    {
        Session::destroy();
        redirect(substr_replace($subDir, '', 0, 1) . '/');
    }
}