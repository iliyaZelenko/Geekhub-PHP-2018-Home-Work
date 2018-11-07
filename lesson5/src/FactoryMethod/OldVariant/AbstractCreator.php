<?php

namespace IlyaZelen\FactoryMethod\OldVariant;

use IlyaZelen\FactoryMethod\OldVariant\Drivers\AbstractDriver;

abstract class AbstractCreator
{
    // тот же init в NewVariant или someOperation если смотреть сюда https://refactoring.guru/ru/design-patterns/factory-method/php/example
    abstract public function init(): AbstractDriver;
}
