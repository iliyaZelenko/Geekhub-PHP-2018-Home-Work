<?php

namespace IlyaZelen\Adapters\GD\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\CropArgumentsTrait;

// Делает обрезку.
class CropCommand extends AbstractCommand
{
    use CropArgumentsTrait;

    /**
     * @param $image
     * @param AbstractAdapter $driver
     */
    public function execute(&$image, AbstractAdapter $driver)
    {
        $this->setArguments();

        $x = $this->argument('x');
        $y = $this->argument('y');
        $width = $this->argument('width');
        $height = $this->argument('height');

        [$originalW, $originalH] = $driver->getSize()->getCompact();

        // чтобы не выходило за границы
        if ($x + $width > $originalW) {
            $width = $originalW - $x;
        }
        if ($y + $height > $originalH) {
            $height = $originalH - $y;
        }

        $image = imagecrop($image, [
            'x' => $x,
            'y' => $y,
            'width' => $width,
            'height' => $height
        ]);
    }
}
