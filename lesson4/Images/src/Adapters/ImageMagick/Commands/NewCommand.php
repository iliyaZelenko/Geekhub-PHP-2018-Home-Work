<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\NewArgumentsTrait;

// Создает новое изображение с заданным размером.
class NewCommand extends AbstractCommand
{
    use NewArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @throws \ImagickException
     */
    public function execute(&$image, AbstractAdapter $driver)
    {
        $this->setArguments();

        $color = $driver->getColor(
            $this->argument('backgroundColor')
        );

        $newImage = new \Imagick();
        $newImage->newImage(
            $this->argument('width'),
            $this->argument('height'),
            $color
        );

        $image = $newImage;
    }
}
