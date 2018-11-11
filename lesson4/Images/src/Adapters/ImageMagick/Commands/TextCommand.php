<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\TextArgumentsTrait;

/*
 * Рисует текст
 */
class TextCommand extends AbstractCommand
{
    use TextArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @return void
     */
    public function execute(&$image, AbstractAdapter $driver): void
    {
        $this->setArguments();

        $text = $this->argument('text');
        $x = $this->argument('x');
        $y = $this->argument('y');
        $color = $this->argument('color');
        $font = $this->argument('font');
        $size = $this->argument('size');
        $angle = $this->argument('angle');


        $color = $driver->getColor($color);
        $font = $driver->getFont($font);

        $draw = new \ImagickDraw();
        $draw->setFillColor($color);
        $draw->setFont($font);
        $draw->setFontSize($size);


        $image->annotateImage(
            $draw,
            $x,
            $y,
            $angle,
            $text
        );
    }
}
