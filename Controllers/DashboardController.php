<?php

namespace Controllers;

use Core\Database;
use Core\Session;
use Core\Validator;
use Core\ResourceInUseException;
 
class DashboardController
{
    private Database $db;
    
    public function __construct()
    {
        $this->db = Database::get();
    }
    
    public function index($subDir)
    {

        $sql = "SELECT 
            p.id AS pid,
            k.id AS kid,
            p.datum_posudbe, 
            c.id AS cid, c.clanski_broj, c.ime, c.prezime,
            k.barcode, k.dostupan, k.film_id, k.medij_id,
            f.naslov, f.cjenik_id,
            m.tip AS medij, m.koeficijent,
            cj.tip_filma, cj. cijena, cj. zakasnina_po_danu AS zakasnina
        FROM posudba p
        JOIN posudba_kopija pk ON p.id = pk.posudba_id
        JOIN kopija k ON k.id = pk.kopija_id
        JOIN filmovi f ON f.id = k.film_id
        JOIN clanovi c ON c.id = p.clan_id
        JOIN cjenik cj ON cj.id = f.cjenik_id
        JOIN mediji m ON m.id = k.medij_id
        WHERE p.datum_povrata IS NULL
        ORDER BY p.id DESC";

        $rentals = $this->db->query($sql)->all();

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

        $movies = [];


        foreach ($results as $key => $movie) {
            if (!array_key_exists($movie['id'], $movies)) {
                
                $movie['formats'][$movie['m_id']]['tip'] = $movie['tip'];
                $movie['formats'][$movie['m_id']]['dostupno'] = $movie['dostupno'];
                $movie['formats'][$movie['m_id']]['nedostupno'] = $movie['nedostupno'];
                $movie['formats'][$movie['m_id']]['ukupno'] = $movie['dostupno'] + $movie['nedostupno'];
                $movies[$movie['id']] = $movie;
            } else { 
                $movies[$movie['id']]['formats'][$movie['m_id']]['tip'] = $movie['tip'];
                $movies[$movie['id']]['formats'][$movie['m_id']]['dostupno'] = $movie['dostupno'];
                $movies[$movie['id']]['formats'][$movie['m_id']]['nedostupno'] = $movie['nedostupno'];
                $movies[$movie['id']]['formats'][$movie['m_id']]['ukupno'] = $movie['dostupno'] + $movie['nedostupno'];
            }
        }

        $sql = "SELECT id, tip
                FROM mediji 
                ORDER BY id";
        $mediji = $this->db->query($sql)->all();

        $sql = "SELECT id, clanski_broj, ime, prezime
                FROM clanovi 
                ORDER BY clanski_broj";
        $clanovi = $this->db->query($sql)->all();

        $pageTitle = 'Dashboard';
        $message = Session::get('message');
        require_once base_path('views/dashboard/index.view.php');
    }
}