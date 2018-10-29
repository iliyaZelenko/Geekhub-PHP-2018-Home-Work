<?php

namespace App\Entity\Human\Teacher;

use App\Entity\Human\HumanAbstract;

class Teacher extends HumanAbstract
{
    public function myMention(): string
    {
        return "Преподаватель {$this->name}";
    }
}
