<?php

namespace Vjezbe;

class Bicikl extends Vehicle implements Driveable
{
   private string $masinica;
   private string $lulaVolana;
   private string $vilica;

   public function getMasa()
   {

   }

   public function drives()
   {
      return 'it rides';
   }

}