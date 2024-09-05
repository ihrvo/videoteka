<?php

namespace Vjezbe;

abstract class Vehicle {

    private string $tip;
    private string $kategorija;
    private int $masa;

    public function __construct(string $tip, string $kategorija, int $masa)
    {
        $this->tip = $tip;
        $this->kategorija = $kategorija;
        $this->masa = $masa;
    }

    abstract function getMasa();

    public function getTip() : string
    {
        return '';
    }
}
