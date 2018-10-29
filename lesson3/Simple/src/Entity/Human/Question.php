<?php

namespace App\Entity\Human;

use App\Entity\Entity;

class Question extends Entity
{
    protected $text;
    protected $from;
    protected $to;
    protected $answer;
    protected $answered;
    protected $answeredBy;

    public function __construct($questionText, HumanAbstract $from, HumanAbstract $to)
    {
        $this->text = $questionText;
        $this->from = $from;
        $this->to = $to;

        draw($from->getMention() . ' спросил у ' . $to->getMention() . ': ' . $questionText);
    }

    public function giveAnswer($text, HumanAbstract $answeredBy): void
    {
        $this->answered = true;
        $this->answer = $text;
        $this->answeredBy = $answeredBy;

        draw($answeredBy->getMention() . ' ответил для ' . $this->from->getMention() . ': ' . $text);
    }

    // все сущности должны указывать как к ним обращаться
    public function myMention(): string
    {
        return 'Вопрос ' . $this->text;
    }
}
