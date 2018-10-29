<?php

namespace App\Entity\Human;

use App\Entity\Entity;
use App\Entity\Traits\FriendableTrait;

abstract class HumanAbstract extends Entity
{
    use FriendableTrait;

    public $age;
    public $name;

    public function myMention(): string
    {
        return $this->name;
    }

    public function __construct($properties)
    {
        $this->age = $properties['age'];
        $this->name = $properties['name'];
    }

    // пример Агрегации (агрегирование по ссылке, то есть после удаления студента, учитель всеравно остается) для параметра $teacher
    public function askQuestion($text, HumanAbstract $to): Question
    {
        // пример Композиции (агрегирование по значению, то есть после удаления студента, его вопросы тоже удаляются)
        $question = new Question($text, $this, $to);

        return $questions[] = $question;
    }

    // тоже пример Агрегации для $question
    public function answerQuestion(Question $question, $text): void
    {
        $question->giveAnswer($text, $this);
    }
}
