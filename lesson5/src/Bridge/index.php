<?php

namespace IlyaZelen\Bridge;
//require '../../vendor/autoload.php';
?><pre><?php


$barrack = new Barrack(
    new Persians()
);
$warrior = $barrack->createWarrior(
    WarriorSwordsman::class
);

echo 'Фракция воина: ' . $warrior->faction::$name . PHP_EOL;
$warrior->attack();




$barrack2 = new BarrackArchers(
    new Greeks()
);
$warrior2 = $barrack2->createWarrior();

echo 'Фракция воина: ' . $warrior2->faction::$name  . PHP_EOL;
$warrior2->attack();




// абстракция (Abstraction), переводится: барак
class Barrack
{
    /**
     * @var Faction
     */
    protected $faction;

    // агрегация
    public function __construct(Faction $faction)
    {
        $this->faction = $faction;
    }

    // абстракция (Barrack) не зависит от реализации (faction)
    public function createWarrior($warriorClass): Warrior
    {
        return $this->faction->createWarrior($warriorClass);
    }
}

// расширенная абстракция (RefinedAbstraction)
class BarrackArchers extends Barrack
{
    public function createWarrior($warriorClass = null): Warrior
    {

        return $this->faction->createWarrior(WarriorArcher::class);
    }
}




// Реализация (Implementor), переводится: Фракция
abstract class Faction
{
    // метод который будет у всех конкретных реализаций
    public function createWarrior($warriorClass): Warrior
    {
        // вот оно пишет что не может найти $name, было бы очень удобно если бы в абстракции можно было указывать свойства какие обязана иметь реализация
        echo 'В фракции ' . static::$name . ' создан воин.' . PHP_EOL;
        // паттерн factoryMethod
        return Warrior::factoryMethod($warriorClass, static::class);
    }
}

// конкретная реализация (ConcreteImplementor)
class Persians extends Faction
{
    public static $name = 'Persians';
}

// конкретная реализация (ConcreteImplementor)
class Greeks extends Faction
{
    public static $name = 'Greeks';
}






// Дальше уже классы не очень связаны с паттерном



// Воин
abstract class Warrior
{
    public $faction;

    public function __construct($faction)
    {
        $this->faction = $faction;
    }

    // паттерн factoryMethod
    public static function factoryMethod(string $warriorClass, string $factionClass)
    {
        return new $warriorClass($factionClass);
    }

    abstract public function attack();
}

class WarriorArcher extends Warrior
{
    public function attack()
    {
        echo 'Лучник атаковал с лука.' . PHP_EOL;
    }
}

class WarriorSwordsman extends Warrior
{
    public function attack()
    {
        echo 'Мечник атаковал с меча.' . PHP_EOL;
    }
}
