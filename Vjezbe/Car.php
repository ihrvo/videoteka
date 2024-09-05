<?php

namespace Vjezbe;

use \Countable;

class Car extends Vehicle implements Driveable, Countable
{
    private string $marka;
    private string $model;
    protected string $gorivo;

    public function __construct(string $marka, string $model, string $gorivo, int $masa, string $tip, string $kategorija)
    {
        $this->marka = $marka;
        $this->model = $model;
        $this->gorivo = $gorivo;

        parent::__construct($tip, $kategorija, $masa);
    }

    public function getMasa()
    {
       
    }

    public function drives()
    {
        return 'it drives';
    }

    public function count(): int {
        return 1;
    }

    public function getMarka()
    {
        return $this->marka;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getGorivo(): string
    {
        return $this->gorivo;
    }

    public function getFullName()
    {
        return "$this->marka - $this->model";
    }

}

$car = new Car('Tesla', 'Model S', 'Electric', 2300, 'Cestovno', 'B');




