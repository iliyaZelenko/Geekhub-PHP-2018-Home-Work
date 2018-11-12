<?php

namespace IlyaZelen\Proxy;


//require '../../vendor/autoload.php';

?><pre><?php


$Vasya = new Driver(14, 'Вася');
$Ilya = new Driver(18, 'Илья');

(new ProxyCar($Vasya))->drive();
(new ProxyCar($Ilya))->drive();
(new Car($Vasya))->drive();




abstract class AbstractCar
{
    protected $driver;


    abstract public function drive();

    public function __construct($driver)
    {
        $this->driver = $driver;
    }
}

class Car extends AbstractCar
{
    public function drive() {
        echo "Водитель {$this->driver} куда-то поехал." . PHP_EOL;
    }
}

class ProxyCar extends AbstractCar
{
    protected $car;

    public function __construct(...$arg)
    {
        parent::__construct(...$arg);
        // Цитата из вкипедии (в контексте Заместителя): "может отвечать за создание или удаление «Реального Субъекта»"
        // То есть Агрегирование (точнее композицию) можно использовать во так
        $this->car = new Car(...$arg);
    }

    public function drive() {
        if ($this->driver->age < 16) {
            echo "Для {$this->driver} запрещено водить: возраст водителя меньше 16 лет ({$this->driver->age})." . PHP_EOL;
            return;
        }

        $this->car->drive();
    }
}

class Driver {
    public $age;
    public $name;

    public function __construct($age, $name)
    {
        $this->age = $age;
        $this->name = $name;
    }

    public function __toString() {
        return $this->name;
    }
}
