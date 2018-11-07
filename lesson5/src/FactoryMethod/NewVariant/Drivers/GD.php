<?php

namespace IlyaZelen\FactoryMethod\NewVariant\Drivers;

// вместого того чтобы использовать конкретный создатель который делает продукт для каждого продукта (product), можно сразу
class GD extends AbstractDriver
{
    public function newImage($width, $height)
    {
        echo 'Картинка создалась на GD.';
    }
}
