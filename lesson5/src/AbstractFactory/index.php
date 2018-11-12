<?php

namespace IlyaZelen\AbstractFactory;
//require '../../vendor/autoload.php';
?><pre><?php



// AbstractFactory
interface TroopsFactoryInterface {
    public function createTank();
    public function createPlane();
}

// Factory
class GermanyFactory implements TroopsFactoryInterface
{
    public function createTank(): Tank
    {
        return new TankGermany();
    }

    public function createPlane(): Plane
    {
        return new PlaneGermany();
    }
}

// Factory
class USSRFactory implements TroopsFactoryInterface
{
    public function createTank(): Tank
    {
        return new TankUSSR();
    }

    public function createPlane(): Plane
    {
        return new PlaneUSSR();
    }
}


// AbstractProduct
interface Tank {}
interface Plane {}

// ConcreteProduct
class TankGermany implements Tank {}
class PlaneGermany implements Plane {}

class TankUSSR implements Tank {}
class PlaneUSSR implements Plane {}





function createArmy(TroopsFactoryInterface $factory) {
    $factory->createTank();
    $factory->createPlane();
}

createArmy(new GermanyFactory());
