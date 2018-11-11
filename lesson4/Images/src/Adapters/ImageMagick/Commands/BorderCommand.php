<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\BorderArgumentsTrait;

/*
 * Рисует рамку
 */
class BorderCommand extends AbstractCommand
{
    use BorderArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @return void
     */
    public function execute(&$image, AbstractAdapter $driver): void
    {
        $this->setArguments();


        $color = $this->argument('color');
        $thickness = $this->argument('thickness');

        $image->borderImage($driver->getColor($color), $thickness, $thickness);
    }
}
