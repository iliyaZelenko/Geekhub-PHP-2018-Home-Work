<?php

namespace App\Entity\Animal\Dog;

use App\Entity\Animal\AnimalAbstract;

class DogAbstract extends AnimalAbstract
{
    protected $nickname;

    public function __construct($properties)
    {
        parent::__construct($properties);

        // кличка
        $this->nickname = $properties['nickname'];
    }

    public function myMention(): string
    {
        return "Собака по кличке {$this->nickname}";
    }
}
