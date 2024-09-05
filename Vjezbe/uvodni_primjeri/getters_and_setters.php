<?php

// declare(strict_types=1);

class Automobil {

    private string $marka;
    private string $model;
    private string $gorivo;
    private int $masa;

    // getter metoda - vraca vrijednost privatnog svojstva izvan klase
    public function getMarka()
    {
        return $this->marka;
    }

    // setter metoda - slazi za postavljnje vrijednosti privatnog svojstva izvan klase
    public function setMarka(string $marka) // TODO: pokazi i objasni povratni tip
    {
        $this->marka = $marka;
        return $this;
    }
    
    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $var)
    {
        $this->model = $var;
        return $this;
    }
    
    public function getMasa(): int
    {
        return $this->masa;
    }

    public function setMasa(int $masa)
    {
        $this->masa = $masa;
        return $this;
    }

    public function getGorivo(): string
    {
        return $this->gorivo;
    }

    public function setGorivo(string $gorivo): self
    {
        $this->gorivo = $gorivo;
        return $this;
    }

    public function getFullName()
    {
        return "$this->marka - $this->model";
    }

    public function toArray()
    {
        return [
            'marka' => $this->marka,
            'model' => $this->model,
            'gorivo' => $this->gorivo,
            'masa' => $this->masa,
        ];
    }
}

$tesla = new Automobil();

$tesla
    ->setMarka('Tesla')
    ->setModel('Model S')
    ->setMasa(2300)
    ->setGorivo('Electric');

echo $tesla->getFullName();
