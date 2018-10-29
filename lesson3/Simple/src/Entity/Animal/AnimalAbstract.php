<?php

namespace App\Entity\Animal;

use App\Entity\Entity;
use App\Entity\Traits\FriendableTrait;

abstract class AnimalAbstract extends Entity
{
    use FriendableTrait;

    public function __construct($properties)
    {
        $this->age = $properties['age'];
        $this->weight = $properties['weight'];
    }
}
