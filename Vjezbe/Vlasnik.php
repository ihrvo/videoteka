<?php

namespace Vjezbe;

class Vlasnik {

    private string $ime;
    private string $prezime;
    private int $godine;
    private string $spol;
    private ?string $adresa;
    private array $cars;

    public function __construct(string $ime, string $prezime, int $godine, string $spol, array $cars = [], ?string $adresa = null)
    {
        $this->ime = $ime;
        $this->prezime = $prezime;
        $this->godine = $godine;
        $this->spol = $spol;
        $this->adresa = $adresa;
        $this->cars = $cars;
    }

    public function hasMany()
    {
        return $this->cars;
    }
}

$modelS = new Car('Tesla', 'Model S', 'Electric', 2300, 'cestovno', 'B');

$model3 = new Car('Tesla', 'Model 3', 'Electric', 1800, 'cestovno', 'B');

$tena = new Vlasnik('Tena', 'Fiskus', 31, 'Zensko', [$modelS, $model3]);
// dd($tena);