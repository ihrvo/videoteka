<?php

// OOP -> Object Oriented Programming

function dd($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}

class Korisnik {

    // svojstva objekta -> properties
    public $ime;
    public $godine;
    public $spol;
    protected $adresa;

    // metode objekta -> methods
    public function posudjujeFilm(){
        // pomocu kljucne rijeci $this pristupamo svojstvima i metodama unutar klase
        $this->ime = 'Alex';

        // varijabla $godine lokalna je unutar metode posudjujeFilm()
        $godine = 39;
        
        $this->seUclanjuje();

        echo $this->ime . ' je posudio Film, on ima ' . $godine . ' godina :-D ';
    }
    
    private function seUclanjuje(){
        echo 'Korisnik je uclanjen ';
    }
}

$tena = new Korisnik();
$tena->ime = 'Tena';
$tena->spol = 'Zensko';
$tena->posudjujeFilm();

$ari = new Korisnik();
$ari->ime = 'Arijan';
$ari->spol = 'Musko';
$ari->posudjujeFilm();

$korisnik = new Korisnik();
$korisnik->ime = 'Aleksandar';
$korisnik->spol = 'Musko';
$korisnik->posudjujeFilm();

// dd($korisnik);