<?php

namespace IlyaZelen\FactoryMethod\NewVariant\Drivers;

class Imagick extends AbstractDriver
{
    public function newImage($width, $height)
    {
        echo 'Картинка создалась на Imagick.';
    }
}
