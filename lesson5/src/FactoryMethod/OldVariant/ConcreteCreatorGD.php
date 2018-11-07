<?php

namespace IlyaZelen\FactoryMethod\OldVariant;

use IlyaZelen\FactoryMethod\OldVariant\Drivers\AbstractDriver;
use IlyaZelen\FactoryMethod\OldVariant\Drivers\GD;

class ConcreteCreatorGD extends AbstractCreator
{
    // фабричный метод (factory method)
    public function init(): AbstractDriver
    {
        return new GD();
    }
}
