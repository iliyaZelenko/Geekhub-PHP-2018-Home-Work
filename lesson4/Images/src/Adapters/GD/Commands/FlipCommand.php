<?php

namespace IlyaZelen\Adapters\GD\Commands;

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
     * @param $image
     * @param AbstractAdapter $driver
     * @return void
     */
    public function execute(&$image, AbstractAdapter $driver): void
    {
        $this->setArguments();


        $mode = $this->argument('mode');

        if ($mode === 'vertical') {
            imageflip($image, IMG_FLIP_VERTICAL);
        }
        if ($mode === 'horizontal') {
            imageflip($image, IMG_FLIP_HORIZONTAL);
        }
        if ($mode === 'both') {
            imageflip($image, IMG_FLIP_BOTH);
        }
    }
}
