<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\OpenArgumentsTrait;

// Считывает изображение по указанному пути.
class OpenCommand extends AbstractCommand
{
    use OpenArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @throws \ImagickException
     */
    public function execute(&$image, AbstractAdapter $driver)
    {
        $this->setArguments();

        $image = new \Imagick($this->argument('path'));
    }
}
