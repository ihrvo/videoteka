<?php

namespace Vjezbe;

class InternalCombustionCar extends Car
{
    public int $zapremina;
    public string $tipGoriva;
    public string $mjenjac;

    public function __construct(int $zapremina, string $tipGoriva, string $mjenjac, string $marka, string $model, string $gorivo, int $masa, string $tip, string $kategorija)
    {
        $this->zapremina = $zapremina;
        $this->tipGoriva = $tipGoriva;
        $this->mjenjac = $mjenjac;

        parent::__construct($marka, $model, $gorivo, $masa, $tip, $kategorija);
    }
}

$car = new InternalCombustionCar(2000, 'benzin', '5 brzina', 'VW', 'Golf', 'benzin', 1500, 'cestovno', 'B');

dd($car);