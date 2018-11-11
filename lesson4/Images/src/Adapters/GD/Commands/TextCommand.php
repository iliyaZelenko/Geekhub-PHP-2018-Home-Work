<?php

namespace IlyaZelen\Adapters\GD\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\TextArgumentsTrait;

/*
 * Рисует текст
 * Заметил что в GD немного больше шрифт чем у Imagick при одинаковых $size
 */
class TextCommand extends AbstractCommand
{
    use TextArgumentsTrait;

    /**
     * @param $image
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

        imagettftext(
            $image,
            $size,
            $angle,
            $x,
            $y,
            $color,
            $font,
            $text
        );
    }
}
