<?php

namespace App\Entity\Human\Student\GoodStudent;

use App\Entity\Human\Student\StudentAbstract;

class GoodStudent extends StudentAbstract
{
    protected $actions = [
        'с интересом слушает преподавателя и делает вид что все понимает.',
        'записывает все сказанное преподавателем в ноут.',
        'активно отвечает на вопросы и хочет себя зарекомендовать.',
        'помог соседу когда тот задал вопрос.'
    ];

    public function lectureProcess(string $placeOfStudy): void
    {
        $this->doRandomAction();
    }
}
