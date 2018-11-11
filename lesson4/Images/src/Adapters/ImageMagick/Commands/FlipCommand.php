<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\FlipArgumentsTrait;

/*
 * Делает переворот картики
 */
class FlipCommand extends AbstractCommand
{
    use FlipArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @return void
     */
    public function execute(&$image, AbstractAdapter $driver): void
    {
        $this->setArguments();


        $mode = $this->argument('mode');

        if ($mode === 'vertical') {
            $image->flipImage();
        }
        if ($mode === 'horizontal') {
            $image->flopImage();
        }
        if ($mode === 'both') {
            $image->flipImage();
            $image->flopImage();
        }
    }
}
