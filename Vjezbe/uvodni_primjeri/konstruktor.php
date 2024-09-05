<?php

include "getters_and_setters.php";

function dd($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}

// TODO: pokazi Constructor Property Promotion

class Vlasnik {

    private Automobil $car;
    private string $ime;
    private string $prezime;
    private int $godine;
    private string $spol;
    // nullable string -> ovaj property moze biti ili sring ili NULL
    private ?string $adresa;
    

    // dodavanje jednod objekta unutar druge klase naziva se Dependency injection
    public function __construct(Automobil $car, string $ime, string $prezime, int $godine, string $spol, ?string $adresa)
    {
        $this->car = $car;
        $this->ime = $ime;
        $this->prezime = $prezime;
        $this->godine = $godine;
        $this->spol = $spol;
        $this->adresa = $adresa;
    }

    public function posjeduje()
    {
        return $this->car;
    }
}

// objekt $tesla kreiarn je u datoteci getters_and_setters.php na liniji 74
$tena = new Vlasnik($tesla, 'Tena', 'Fiskus', 31, 'Zensko', null);

$tena->posjeduje();
dd($tena);