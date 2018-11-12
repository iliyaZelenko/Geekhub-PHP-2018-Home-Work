<?php

namespace IlyaZelen\FactoryMethod\OldVariant\Drivers;

// вместого того чтобы использовать конкретный создатель который делает продукт для каждого продукта (product), можно сразу
class Imagick extends AbstractDriver
{
    public function newImage($width, $height)
    {
        echo 'Картинка создалась на Imagick.';
    }
}
