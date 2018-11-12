<?php

namespace IlyaZelen\FactoryMethod\NewVariant\Drivers;

/*
 * абстрактный продукт
 * класс который берет на себя роли абстрактного создателя (creator)
 * вместого того чтобы использовать конкретный создатель который делает продукт, можно создавать продукт тут, в фабричном методе
 * (не в фабричном методе конкретного создателя)
 * я так понял важно чтобы фабричный метод init был именно в абстрактном классе который для продуктов
*/
abstract class AbstractDriver
{
    public static function init($driver)
    {
        $class = '\IlyaZelen\FactoryMethod\NewVariant\Drivers\\' . $driver;

        return new $class();
    }

    abstract public function newImage($width, $height);
}
