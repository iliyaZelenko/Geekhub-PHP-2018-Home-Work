<?php

namespace IlyaZelen\Adapters\GD\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\NewArgumentsTrait;

// Создает новое изображение с заданным размером.
class NewCommand extends AbstractCommand
{
    use NewArgumentsTrait;

    public function execute(&$image, AbstractAdapter $driver)
    {
        $this->setArguments();

        $image = $driver->createTransparentImage(
            $this->argument('width'),
            $this->argument('height')
        );

        $color = $driver->getColor(
            $this->argument('backgroundColor')
        );
        imagefill($image, 0, 0, $color);
    }
}
