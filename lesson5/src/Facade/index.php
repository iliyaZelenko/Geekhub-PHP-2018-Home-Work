<?php

namespace IlyaZelen\Facade;


require '../../vendor/autoload.php';

?><pre><?php


(new World)
    // создание подсистемы через фасад
    ->create()
    // фасад — единственная точка взаимодействия с классами подсистемы.
    // Я так понимаю на прямую при использовании этого фасада создавать Planet нельзя или не желательно, иначе нарушается правило (цитата из википедии):
    // "Реализация других компонентов подсистемы закрыта и не видна внешним компонентам.".
    // Возожно имеется в виду нет доступа к компонентам подсистемы если они в подсистеме, но если и в другом месте могут использоватся, то можно и там использовать.
    ->addCustomPlanet()
    ->startApocalypse()
;

// Классы подсистемы:
// галактика
class Galaxy {}

class Planet {}

// черная дыра
class BlackHole {}


// Фасад
class World {
    protected $planets = [];
    protected $galaxies = [];
    protected $blackHoles = [];

    // публичные методы — единственные способы взаимодействия с подсистемой
    public function create()
    {
        // вызывает внутренние (protected / private) методы
        $this->createGalaxies();
        $this->createPlanets();
        $this->createBlackHoles();

        echo 'Мир создан.' . PHP_EOL;

        return $this;
    }

    public function addCustomPlanet()
    {
        $this->planets[] = new Planet();

        echo 'Добавленна произвольная планета.' . PHP_EOL;

        return $this;
    }

    // начинает апокалипсис
    public function startApocalypse()
    {
        $this->planets[] = [];
        $this->galaxies[] = [];

        echo 'Начат апокалипсис.' . PHP_EOL;
    }

    protected function createGalaxies()
    {
        array_push(
            $this->galaxies,
            new Galaxy(),
            new Galaxy()
        );
    }

    protected function createPlanets()
    {
        array_push(
            $this->planets,
            new Planet(),
            new Planet(),
            new Planet()
        );
    }

    protected function createBlackHoles()
    {
        $this->blackHoles[] = new BlackHole();
    }
}


