<?php

namespace Controllers;

use Core\Database;
use Core\Session;
use Core\Validator;
use Core\ResourceInUseException;

class MoviesController
{
    private Database $db;


    public function __construct()
    {
        $this->db = Database::get();
    }

    public function index($subDir)
    {
        function icon($medij)
        {
            return match ($medij) {
                'DVD' => 'disc-fill text-warning',
                'Blu-ray' => 'disc text-primary',
                'VHS' => 'cassette-fill text-success',
                default => 'disc text-secondary'
            };
        }

        $sql = "SELECT 
            f.id,
            f.naslov,
            f.godina,
            z.ime AS zanr,
            m.tip AS tip,
            m.id AS m_id,
            c.tip_filma,
            COUNT(CASE WHEN k.dostupan = 1 THEN 1 END) AS dostupno,
            COUNT(CASE WHEN k.dostupan = 0 THEN 1 END) AS nedostupno
        FROM
            filmovi f
            LEFT JOIN kopija k ON f.id = k.film_id
            LEFT JOIN zanrovi z ON z.id = f.zanr_id
            LEFT JOIN mediji m ON m.id = k.medij_id
            JOIN cjenik c ON c.id = f.cjenik_id
        GROUP BY f.id, m.id
        ORDER BY f.id";
        
        

        $results = $this->db->query($sql)->all();
        // foreach ($results as $value) {
        //     echo $value['id'];
        // }

        // dd($results);
        $movies = [];

        foreach ($results as $key => $movie) {
            if (!array_key_exists($movie['id'], $movies)) {
                
                $movie['formats'][$movie['m_id']]['tip'] = $movie['tip'];
                $movie['formats'][$movie['m_id']]['dostupno'] = $movie['dostupno'];
                $movie['formats'][$movie['m_id']]['nedostupno'] = $movie['nedostupno'];
                $movie['formats'][$movie['m_id']]['ukupno'] = $movie['dostupno'] + $movie['nedostupno'];
                $movie['formats'][$movie['m_id']]['icon'] = icon($movie['tip']);
                $movies[$movie['id']] = $movie;
            } else { 
                $movies[$movie['id']]['formats'][$movie['m_id']]['tip'] = $movie['tip'];
                $movies[$movie['id']]['formats'][$movie['m_id']]['dostupno'] = $movie['dostupno'];
                $movies[$movie['id']]['formats'][$movie['m_id']]['nedostupno'] = $movie['nedostupno'];
                $movies[$movie['id']]['formats'][$movie['m_id']]['ukupno'] = $movie['dostupno'] + $movie['nedostupno'];
                $movies[$movie['id']]['formats'][$movie['m_id']]['icon'] = icon($movie['tip']);
            }

        }
        // dd($movies);
        $pageTitle = 'Filmovi';
        $message = Session::get('message');  
        require base_path('views/movies/index.view.php');
        
    }

    public function show($subDir)
    {
       if (!isset($_GET['id'])) {
            abort();
        }

        $sql = "SELECT
        f.id,
        f.naslov,
        f.godina,
        z.ime AS zanr,
        c.tip_filma
        from
            filmovi f
            JOIN cjenik c ON f.cjenik_id = c.id
            JOIN zanrovi z ON f.zanr_id = z.id
        WHERE 
            f.id = :id";
        $movie = $this->db->query($sql, ['id' => $_GET['id']])->findOrFail();
        $pageTitle = 'Film';

        require base_path('views/movies/show.view.php');
    }

    public function edit($subDir)
    {
        $sql = "SELECT * FROM zanrovi ORDER BY id";
        $genres = $this->db->query($sql)->all();


        $sql = "SELECT * FROM cjenik ORDER BY id";
        $prices = $this->db->query($sql)->all();

        $sql = "SELECT
        f.id,
        f.naslov,
        f.godina,
        z.ime AS zanr,
        c.tip_filma
        from
            filmovi f
            JOIN cjenik c ON f.cjenik_id = c.id
            JOIN zanrovi z ON f.zanr_id = z.id
        WHERE 
            f.id = :id";
        $movie = $this->db->query($sql, ['id' => $_GET['id']])->findOrFail();
        $errors = Session::get('errors');
        require base_path('views/movies/edit.view.php');
        
    }

    public function update($subDir)
    {
        if (!isset($_POST['id'])) {
            abort();
        }
        $rules = [
            'naslov' => ['required', 'string', 'min:2', 'max:100'],
            'godina' => ['required', 'numeric', 'min:4', 'max:4'],
            'zanr_id' => ['required', 'numeric'],
            'cjenik_id' => ['required', 'numeric']
        ];
        
        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }
        
        $data = $form->getData();
        // dd($data);
        
        $sql = "UPDATE filmovi SET naslov = :naslov, godina = :godina, zanr_id = :zanr_id, cjenik_id = :cjenik_id WHERE id = :id";
        $this->db->query($sql, [
            'naslov' => $data['naslov'],
            'godina' => $data['godina'],
            'zanr_id' => $data['zanr_id'],
            'cjenik_id' => $data['cjenik_id'],
            'id' => $_POST['id']
        ]);
        
        Session::flash('message', [
            'type' => 'success',
            'message' => "Film uspješno spremljen"
        ]);
        redirect(substr_replace($subDir, '', 0, 1) . '/movies');
       
    }

    public function create($subDir)
    {
        $sql = "SELECT * FROM zanrovi ORDER BY id";
        $genres = $this->db->query($sql)->all();

        $sql = "SELECT * FROM cjenik ORDER BY id";
        $prices = $this->db->query($sql)->all();

        $sql = "SELECT tip FROM mediji ORDER BY id";
        $mediji = $this->db->query($sql)->all();
        $mediji_count = $this->db->query($sql)->ukupno();
        (int)$columns = round(12/$mediji_count);
        $errors = Session::get('errors');
        require base_path('views/movies/create.view.php');
 
    }

    public function store()
    {
        $rules = [
            'naslov' => ['required', 'string', 'min:2', 'max:100'],
            'godina' => ['required', 'numeric', 'min:4', 'max:4'],
            'zanr_id' => ['required', 'numeric'],
            'cjenik_id' => ['required', 'numeric']
        ];
        
        $form = new Validator($rules, $_POST);
        if ($form->notValid()){
            Session::flash('errors', $form->errors());
            goBack();
        }
        
        $data = $form->getData();
        //  dd($data);
        
        $sql = "INSERT INTO filmovi (naslov, godina, zanr_id, cjenik_id) VALUES (:naslov, :godina, :zanr_id, :cjenik_id)";
        
        if($this->db->query($sql, [
            'naslov' => $data['naslov'],
            'godina' => $data['godina'],
            'zanr_id' => $data['zanr_id'],
            'cjenik_id' => $data['cjenik_id']
        ])) {
                $sql = "SELECT id FROM filmovi ORDER BY id DESC LIMIT 1";
                $lastID = $this->db->query($sql)->find();
                // dd($lastID['id']);
                $sql = "SELECT id, tip FROM mediji ORDER BY id";
                $mediji = $this->db->query($sql)->all();
                foreach ($mediji as $medij):
                    $i = 0;
                    if(isset($_POST[$medij['tip']])) {
                        $barcode = strtoupper(preg_replace('/[^a-zA-Z]/', '', $data['naslov'] . $medij['tip']));
                        // dd($barcode);
                        $sql = "INSERT INTO kopija (barcode, dostupan, film_id, medij_id) VALUES (:barcode, :dostupan, :film_id, :medij_id)";
                        for($i = 0; $i < (int)$_POST[$medij['tip']]; $i++) {
                            $this->db->query($sql, [
                                'barcode' => $barcode,
                                'dostupan' => 1,
                                'film_id' => $lastID['id'],
                                'medij_id' => $medij['id']
                            ]);
                        }
                    }
                endforeach;
        }

        Session::flash('message', [
            'type' => 'success',
            'message' => "Film uspješno dodan"
        ]);
        redirect('videoteka/movies');
        
 
        
    }

    public function destroy()
    {
        if (!isset($_POST['id'] ) || !isset($_POST['_method']) || $_POST['_method'] !== 'DELETE') {
            abort();
        }
        
        $sql = 'SELECT * from filmovi WHERE id = :id';
        $medij = $this->db->query($sql, ['id' => $_POST['id']])->findOrFail();
        
        $sql = "DELETE from filmovi WHERE id = :id";
        
        try {
            $this->db->query($sql, ['id' => $_POST['id']]);
            Session::flash('message', [
                'type' => 'success',
                'message' => "Film uspješno obrisan"
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            Session::flash('message', [
                'type' => 'danger',
                'message' => "Ne možete obrisati film koji ima aktivne posudbe"
            ]);
        }
        
        redirect('videoteka/movies');
     
    }
}