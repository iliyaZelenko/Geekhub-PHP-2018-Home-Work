<?php

namespace IlyaZelen\FactoryMethod\NewVariant\Drivers;

class GD extends AbstractDriver
{
    public function newImage($width, $height)
    {
        echo 'Картинка создалась на GD.';
    }
}
