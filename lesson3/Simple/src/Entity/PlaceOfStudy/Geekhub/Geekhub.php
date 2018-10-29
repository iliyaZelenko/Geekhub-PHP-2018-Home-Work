<?php

namespace App\Entity\PlaceOfStudy\Geekhub;

use App\Entity\PlaceOfStudy\PlaceOfStudyAbstract;

class Geekhub extends PlaceOfStudyAbstract
{
    public static $name = 'GeekHub';
    public static $description = 'Курсы по Web технологиям в Черкассах.';

    public function myMention(): string
    {
        return static::$name;
    }
}
