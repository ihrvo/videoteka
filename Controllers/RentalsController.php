<?php

namespace Controllers;

use Core\Database;
use Core\Session;
use Core\Validator;
use Core\ResourceInUseException;

class RentalsController
{
    private Database $db;


    public function __construct()
    {
        $this->db = Database::get();
    }

    public function index($subDir)
    {
        $message = Session::get('message');

        $sql = "SELECT p.*,
                c.clanski_broj, c.ime, c.prezime,
                pk.kopija_id,
                k.barcode, k.dostupan, k.film_id, k.medij_id,
                f.naslov, f.cjenik_id,
                m.tip AS medij, m.koeficijent,
                cj.tip_filma, cj. cijena, cj. zakasnina_po_danu AS zakasnina
            FROM posudba p 
            JOIN clanovi c ON c.id = p.clan_id
            JOIN posudba_kopija pk ON p.id = pk.posudba_id
            JOIN kopija k ON pk.kopija_id = k.id
            JOIN filmovi f ON k.film_id = f.id
            JOIN mediji m ON k.medij_id = m.id
            JOIN cjenik cj ON f.cjenik_id = cj.id
            ORDER BY p.id DESC";

        $rentals = $this->db->query($sql)->all();

        $pageTitle = 'Posudbe';
        require base_path('views/rentals/index.view.php');
    }

    public function show($subDir)
    {
        if (!isset($_GET['pid'])) {
            abort();
        }
        
        $sql = "SELECT 
            p.id, k.id AS kid,
            p.datum_posudbe,
            p.datum_povrata,
            CONCAT(c.clanski_broj, ' ' , c.ime, ' ', c.prezime) AS clan, 
            f.naslov, 
            m.tip,
            CONCAT(cj.cijena, ' € - ', cj.tip_filma) AS cijena_tip_filma,
            cj.zakasnina_po_danu,
            cj.cijena
            FROM posudba p
            JOIN posudba_kopija pk ON p.id = pk.posudba_id
            JOIN kopija k ON k.id = pk.kopija_id
            JOIN filmovi f ON f.id = k.film_id
            JOIN clanovi c ON c.id = p.clan_id
            JOIN cjenik cj ON cj.id = f.cjenik_id
            JOIN mediji m ON m.id = k.medij_id
            WHERE p.id = :id
            ORDER BY p.datum_posudbe DESC";

        $posudbe = $this->db->query($sql, ['id' => $_GET['pid']])->findOrFail();
        
        $danas = new \DateTime(date('Y-m-d'));
        $datum_posudbe = new \DateTime($posudbe['datum_posudbe']);
        
        $dani_posudbe = $datum_posudbe->diff($danas)->days;
        
        $vratiti_do_datuma = $datum_posudbe->modify('+1 day');
        if ($danas > $vratiti_do_datuma) {
            $dani_kasnjenja = $vratiti_do_datuma->diff($danas)->days;
        }
        else {
            $dani_kasnjenja = 0;
        }
        
        $zakasnina = $posudbe['zakasnina_po_danu'] * $dani_kasnjenja;
        $dugovanje = $zakasnina + $posudbe['cijena'];
        
        $pageTitle = 'Posudba';
        
        require base_path('views/rentals/show.view.php');
       
    }

    public function edit($subDir)
    {
        if (!isset($_GET['pid'])) {
            abort();
        }
        
        $sql = "SELECT 
            p.id AS pid,
            k.id AS kid, 
            p.datum_posudbe,
            p.datum_povrata,
            CONCAT(c.clanski_broj, ' ' , c.ime, ' ', c.prezime) AS clan, 
            f.naslov, 
            m.tip,
            CONCAT(cj.cijena, ' € - ', cj.tip_filma) AS cijena_tip_filma,
            cj.zakasnina_po_danu,
            cj.cijena,
            f.id AS film_id
            FROM posudba p
            JOIN posudba_kopija pk ON p.id = pk.posudba_id
            JOIN kopija k ON k.id = pk.kopija_id
            JOIN filmovi f ON f.id = k.film_id
            JOIN clanovi c ON c.id = p.clan_id
            JOIN cjenik cj ON cj.id = f.cjenik_id
            JOIN mediji m ON m.id = k.medij_id
            WHERE p.id = :id
            ORDER BY p.datum_posudbe DESC";
        $posudbe = $this->db->query($sql, ['id' => $_GET['pid']])->findOrFail();
        
        $danas = new \DateTime(date('Y-m-d'));
        $datum_posudbe = new \DateTime($posudbe['datum_posudbe']);
        
        $dani_posudbe = $datum_posudbe->diff($danas)->days;
        
        $vratiti_do_datuma = $datum_posudbe->modify('+1 day');
        if ($danas > $vratiti_do_datuma) {
            $dani_kasnjenja = $vratiti_do_datuma->diff($danas)->days;
        }
        else {
            $dani_kasnjenja = 0;
        }
        
        
        $zakasnina = $posudbe['zakasnina_po_danu'] * $dani_kasnjenja;
        $dugovanje = $zakasnina + $posudbe['cijena'];
        
        $pageTitle = 'Uredi posudbu';
        
        $sql = "SELECT id,godina, naslov
                FROM filmovi 
                ORDER BY godina";
        $movies = $this->db->query($sql)->all();
        
        $sql = "SELECT id, tip
                FROM mediji 
                ORDER BY id";
        $mediji = $this->db->query($sql)->all();
        
        $sql = "SELECT id, CONCAT(clanski_broj, ' ' , ime, ' ', prezime) AS clan 
                FROM clanovi 
                ORDER BY clanski_broj";
        $clanovi = $this->db->query($sql)->all();
        $errors = Session::get('errors');
        require base_path('views/rentals/edit.view.php');
        
    }

    public function update($subDir)
    {
        $datum = date('Y-m-d');
        // dd($_POST);
        $rules = [
            'pid' => ['required', 'numeric','max:5'],
            'clan_id' => ['required', 'numeric','max:5'],
            'film_id' => ['required', 'numeric','max:5'],
            'medij_id' => ['required', 'numeric','max:5'],
            'datum_posudbe' => ['required', 'date'],
            'datum_povrata' => ['date']
        ];

        //TODO: validate the data

        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }

        $data = $form->getData();
        // dd($data);
        $sql ="SELECT f.id AS film_id, m.id AS medij_id 
            FROM posudba_kopija pk 
            JOIN kopija k ON k.id = pk.kopija_id 
            JOIN filmovi f ON f.id = k.film_id 
            JOIN mediji m ON m.id = k.medij_id 
            WHERE pk.posudba_id = :id";
        // dd($data['pid']);
        $film_medij = $this->db->query($sql, ['id' => $data['pid']])->find();
        //  dd($film_medij['medij_id']);

        if ($data['film_id'] != $film_medij['film_id'] || $data['medij_id'] != $film_medij['medij_id']) {
            // oslobodi kopiju
            $sql = "UPDATE kopija SET dostupan = 1 WHERE film_id = :film_id AND dostupan = 0 AND medij_id = :medij_id LIMIT 1";
            $this->db->query($sql, [
                'film_id' => $film_medij['film_id'],
                'medij_id'=> $film_medij['medij_id']
            ]);

            // odaberi novu kopiju
            $sql = "SELECT id FROM kopija WHERE film_id = :film_id   AND dostupan = 1 AND medij_id = :medij_id LIMIT 1;";
            $nova_kopija_id = $this->db->query($sql, [
                'film_id' => $data['film_id'],
                'medij_id' =>$data['medij_id']
            ])->find();
            if(empty($nova_kopija_id)){
                die("Odabrani film nije dostupan.");
            } else {
                // označi nedustupnom novu kopiju
                $sql = "UPDATE kopija SET dostupan = 0 WHERE film_id = :film_id AND dostupan = 1 AND medij_id = :medij_id LIMIT 1";
                $this->db->query($sql, [
                    'film_id' => $data['film_id'],
                    'medij_id' =>$data['medij_id']
                ]);
                $sql = "UPDATE posudba_kopija SET kopija_id = :kopija_id WHERE posudba_id = :posudba_id";
                $this->db->query($sql, [
                    'kopija_id' => $nova_kopija_id['id'],
                    'pid' =>$data['pid']
                ]);
            }
            
        } 

        if ($_POST['datum_povrata'] === "") {$data['datum_povrata'] = NULL;}

        $sql = "UPDATE posudba SET clan_id = :clan_id, datum_posudbe = :datum_posudbe, datum_povrata = :datum_povrata WHERE id = :id";
                $this->db->query($sql, [
                    'clan_id' => $data['clan_id'],
                    'datum_posudbe' => $data['datum_posudbe'],
                    'datum_povrata' => $data['datum_povrata'],
                    'id' => $data['pid']
                ]);
                
                redirect(substr_replace($subDir, '', 0, 1) . '/rentals/show?id=' . $data['pid'] );
        // goBack();

       
    }

    public function create($subDir)
    {
        $message = Session::get('message');
        $sql = "SELECT id,godina, naslov
        FROM filmovi 
        ORDER BY godina";
        $movies = $this->db->query($sql)->all();

        $sql = "SELECT id, tip
                FROM mediji 
                ORDER BY id";
        $mediji = $this->db->query($sql)->all();

        $sql = "SELECT id, clanski_broj, ime, prezime
                FROM clanovi 
                ORDER BY clanski_broj";
        $clanovi = $this->db->query($sql)->all();


        $sql = "SELECT * FROM cjenik ORDER BY id";
        $prices = $this->db->query($sql)->all();

        $errors = Session::get('errors');
        $pageTitle = 'Nova posudba';
        require base_path('views/rentals/create.view.php');
        
    }

    public function store($subDir)
    {
        $datum = date('Y-m-d');
        // dd($datum);
        $rules = [
            'clan_id' => ['numeric','max:5', 'required'],
            'film_id' => ['numeric','max:5', 'required'],
            'medij_id' => ['numeric','max:5', 'required']
        ];

        //TODO: validate the data

        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }

        $data = $form->getData();

        $sql = "SELECT k.id, k.film_id, f.naslov
                FROM kopija k
                JOIN filmovi f ON f.id = k.film_id
                WHERE film_id = :film_id AND dostupan = 1 AND medij_id= :medij_id";
        $count = $this->db->query($sql, ['film_id' => $data['film_id'], 'medij_id' => $data['medij_id']])->find();

        if(empty($count)){
            Session::flash('message', [
                'type' => 'danger',
                'message' => "Odabrani film trenutno nije dostupan"
            ]);
            goBack();
        }
        else {
            $sql = "UPDATE kopija SET dostupan = 0 WHERE film_id = :film_id AND dostupan = 1 AND medij_id = :medij_id LIMIT 1";
            $this->db->query($sql, [
                'film_id' => $data['film_id'],
                'medij_id' =>$data['medij_id']
            ]);
        }


        $sql = "INSERT INTO posudba (datum_posudbe, clan_id) VALUES (:datum_posudbe, :clan_id)";
        if($this->db->query($sql, [
            'datum_posudbe' => $datum,
            'clan_id' => $data['clan_id']
        ]))
        {

        $sql = "SELECT id FROM posudba ORDER BY id DESC LIMIT 1";
        $lastID = $this->db->query($sql)->find();
        // dd($lastID['id']);
        $sql = "INSERT INTO posudba_kopija (posudba_id, kopija_id) VALUES (:pid, :kopija_id)";
        $this->db->query($sql, [
            'pid' => $lastID['id'],
            'kopija_id' => $count['id']
        ]);
        }
        Session::flash('message', [
            'type' => 'success',
            'message' => "Posudba uspješno kreirana"
        ]);

        if (str_contains($_SERVER['HTTP_REFERER'], 'dashboard')) {
            redirect(substr_replace($subDir, '', 0, 1) . '/dashboard');
        } else {
            redirect(substr_replace($subDir, '', 0, 1) . '/rentals');
        }
        
        // goBack();
    }

    public function destroy()
    {

        $rental = $this->db->query('SELECT * from posudba WHERE id = :id', ['id' => $_POST['pid']])->findOrFail();
        $copy   = $this->db->query("SELECT * from kopija WHERE id = :id", ['id' => $_POST['kid']])->findOrFail();

        $rentals = $this->db->query('SELECT posudba_id from posudba_kopija WHERE posudba_id = :pid', ['pid' => $_POST['pid']])->all();
        // dd(count($rentals));

        try {
            $this->db->connection()->beginTransaction(); 

            if (count($rentals) == 1) {
                // samo jedna kopija je u posudbi, oznaci posudbu kao vraceno
                $this->db->query("DELETE from posudba_kopija WHERE posudba_id = :pid AND kopija_id = :kid", [
                    'pid' => $rental['id'],
                    'kid' => $copy['id'],
                ]);
            
                $this->db->query('UPDATE posudba SET datum_povrata = ? WHERE id = ?', [
                    date('Y-m-d'), $_POST['pid']
                ]);
            }else{
                // posudba ima jos ne vracenih koopija , samo osvjezi updated_at
                $this->db->query("UPDATE posudba SET updated_at = :d WHERE id = :pid", [
                    'pid' => $rental['id'],
                    'd' => date("Y-m-d H:i:s")
                ]);
            }

            $this->db->query("UPDATE kopija SET dostupan = 1 WHERE id = :kid", ['kid' => $copy['id']]);
        } catch (ResourceInUseException $e) {
            $this->db->connection()->rollBack();
            Session::flash('message', [
                'type' => 'danger',
                'message' => 'Something wrong'
            ]);
            goBack();
        }

        $this->db->connection()->commit();

        Session::flash('message', [
            'type' => 'success',
            'message' => "Uspjesno vracena kopija {$copy['id']}"
        ]);
        goBack();
     
    }
}