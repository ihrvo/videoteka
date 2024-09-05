<?php

namespace Vjezbe;

class ElectricCar extends Car
{
    public int $kilovati;
    public string $tipBaterije;
    public string $punjac;

    public function __construct(int $kilovati, string $tipBaterije, string $punjac, string $marka, string $model, string $gorivo, int $masa, string $tip, string $kategorija)
    {
        $this->kilovati = $kilovati;
        $this->tipBaterije = $tipBaterije;
        $this->punjac = $punjac;

        parent::__construct($marka, $model, $gorivo, $masa, $tip, $kategorija);
    }
}

$electricCar = new ElectricCar(95, 'NMC', 'CCS', 'Tesla', 'Model S', 'electric', 2300, 'cestovno', 'B');

dd($electricCar);