<?php

namespace App\Entity\Animal\Dog\Sheepdog;

use App\Entity\Animal\Dog\DogAbstract;

// Немецкая овчарка
class GermanShepherd extends DogAbstract
{
    protected $origin;

    public function __construct($properties)
    {
        parent::__construct($properties);

        // происхождение
        $this->origin = 'Germany';
    }
}
