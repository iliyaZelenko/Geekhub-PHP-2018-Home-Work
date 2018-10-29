<?php

namespace App\Entity;

abstract class Entity
{
    // как упоминать
    abstract public function myMention(): string;

    // получить упоминание
    public function getMention(): string
    {
        return '<b>' . static::myMention() . '</b>';
    }
}
