<?php

namespace App\Entity\Human\Student;

use App\Entity\Human\HumanAbstract;

abstract class StudentAbstract extends HumanAbstract
{
    protected $placesOfStudy = [];
    protected $actions = [];
    protected $questions = [];

    abstract public function lectureProcess(string $placeOfStudyAlias);

    public function addPlaceOfStudy(string $placeOfStudy): void
    {
        echo $this->getMention() . ' поступил на новое место обучения — ' . $placeOfStudy::getMention() . '.' . PHP_EOL;
        $this->placesOfStudy[] = $placeOfStudy;
    }

    public function myMention(): string
    {
        return "Студент {$this->name}";
    }

    public function doRandomAction(): void
    {
        $randomKey = array_rand($this->actions, 1);

        draw("{$this->getMention()} {$this->actions[$randomKey]}");
    }
}
