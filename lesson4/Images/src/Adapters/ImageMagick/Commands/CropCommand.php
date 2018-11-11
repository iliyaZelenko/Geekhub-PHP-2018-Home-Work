<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\CropArgumentsTrait;

// Делает обрезку.
class CropCommand extends AbstractCommand
{
    use CropArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     */
    public function execute(&$image, AbstractAdapter $driver)
    {
        $this->setArguments();

        $image->cropImage(
            $this->argument('width'),
            $this->argument('height'),
            $this->argument('x'),
            $this->argument('y')
        );
    }
}
