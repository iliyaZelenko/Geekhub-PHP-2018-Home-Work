<?php

namespace App\Entity\Human\Student\BadStudent;

use App\Entity\Human\Student\StudentAbstract;

class BadStudent extends StudentAbstract
{
    protected $actions = [
        'играет на ноуте и не хочет слушать преподавателя.',
        'разговаривает с соседями и не дает им слушать.',
        'сидит в наушниках слушая музыку.',
        'задает без остановки глупые вопросы.'
    ];

    public function lectureProcess(string $placeOfStudy): void
    {
        $this->doRandomAction();
    }
}
