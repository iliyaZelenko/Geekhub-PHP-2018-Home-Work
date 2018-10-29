<?php

namespace App\Entity\Animal\Dog\Molossy;

use App\Entity\Animal\Dog\DogAbstract;

// Ротвейлер
class Rottweiler extends DogAbstract
{
    protected $color;

    public function __construct($properties)
    {
        parent::__construct($properties);

        // происхождение
        $this->color = 'black';
    }
}
