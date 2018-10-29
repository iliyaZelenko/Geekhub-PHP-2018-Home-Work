<?php

namespace App\Entity\Animal\Dog\Sheepdog;

use App\Entity\Animal\Dog\DogAbstract;

// Валлийская овчарка
class IcelandicSheepdog extends DogAbstract
{
    protected $lifeSpan;

    public function __construct($properties)
    {
        parent::__construct($properties);

        // срок жизни
        $this->lifeSpan = 12;
    }
}
