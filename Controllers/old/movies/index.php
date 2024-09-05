<?php

use Core\Database;

$db = Database::get();

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
m.tip,
m.id AS m_id,
f.godina,
z.ime AS zanr,
c.tip_filma,
COUNT(CASE WHEN k.dostupan = 1 THEN 1 END) AS dostupno,
COUNT(CASE WHEN k.dostupan = 0 THEN 1 END) AS nedostupno
FROM
    filmovi f
    JOIN cjenik c ON f.cjenik_id = c.id
    JOIN zanrovi z ON f.zanr_id = z.id
    JOIN kopija k ON k.film_id = f.id
    JOIN mediji m ON m.id = k.medij_id
    GROUP BY f.id, m.id
ORDER BY f.id";

$results = $db->query($sql)->all();
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

require base_path('views/movies/index.view.php');