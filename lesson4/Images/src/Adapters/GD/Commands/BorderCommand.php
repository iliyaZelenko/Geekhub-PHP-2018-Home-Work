<?php

namespace IlyaZelen\Adapters\GD\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\BorderArgumentsTrait;

/*
 * Рисует рамку
 * В GD рисует немного криво если она повернутая
 */
class BorderCommand extends AbstractCommand
{
    use BorderArgumentsTrait;

    /**
     * @param $image
     * @param AbstractAdapter $driver
     * @return void
     */
    public function execute(&$image, AbstractAdapter $driver): void
    {
        $this->setArguments();


        $color = $this->argument('color');
        $thickness = $this->argument('thickness');

        $x1 = 0;
        $y1 = 0;
        $x2 = imagesx($image) - 1;
        $y2 = imagesy($image) - 1;

        for ($i = 0; $i < $thickness; $i++) {
            // координаты начала и конца идут в центр благодрая их инкременту и декременту
            imagerectangle(
                $image,
                $x1++, $y1++,
                $x2--, $y2--,
                $driver->getColor($color)
            );
        }
    }
}
