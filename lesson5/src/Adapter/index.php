<?php

namespace IlyaZelen\Proxy;


//require '../../vendor/autoload.php';

?><pre><?php

// интерфейс к которому будет обращатся Client (Target)
interface ImageInterface {
    public function newImage(): ImageInterface;

    public function resize(): ImageInterface;
}

// Adapter для реализации на GD
class AdapterGD implements ImageInterface {
    public function newImage(): ImageInterface
    {
        // Adaptee реализация GD, по сути Adapter является Proxy для реализации Adaptee
        echo 'Новая картинка на GD';

        return $this;
    }

    public function resize(): ImageInterface
    {
        // Adaptee реализация GD
        echo 'Ресайз на GD';

        return $this;
    }
}

// Adapter для реализации на Imagick
class AdapterImagick implements ImageInterface {
    public function newImage(): ImageInterface
    {
        // Adaptee реализация Imagick
        echo 'Новая картинка на Imagick';

        return $this;
    }

    public function resize(): ImageInterface
    {
        // Adaptee реализация Imagick
        echo 'Ресайз на Imagick';

        return $this;
    }
}

// Client использует нужный Adapter и легко может поменять на другой
$image = (new AdapterGD())
    ->newImage()
    ->resize();
