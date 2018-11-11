<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\RotateArgumentsTrait;

/*
 * Делает поворот изображения. При повороте по умолчанию изображение не обрезается, обрезка включается через $crop парметр.
 * Вообще хотел сделать такую обрезку: https://i.imgur.com/gpQchqH.png --> https://i.imgur.com/vwqn7QF.png, но там много нюансов.
 * Вот ее алгоритм (что хотел сделать): https://stackoverflow.com/questions/5789239/calculate-largest-rectangle-in-a-rotated-rectangle/22511805#22511805
 */
class RotateCommand extends AbstractCommand
{
    use RotateArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @return AbstractAdapter
     */
    public function execute(&$image, AbstractAdapter $driver): AbstractAdapter
    {
        $this->setArguments();


        $angle = $this->argument('angle');
        $color = $driver->getColor(
            $this->argument('backgroundColor')
        );
        $crop = $this->argument('crop');


        // если нужно обрезать в конце, то запоминает размеры до поворота
        if ($crop) {
            [$oldW, $oldH] = $driver->getSize()->getCompact();
        }

        $image->rotateImage($color, $angle);

        // обрезает края
        if ($crop) {
            [$newW, $newH] = $driver->getSize()->getCompact();

            // Координаты начала обрезки.
            // Новая длинна/высота может быть меньше старой, поэтому меньше нуля не нужно брать.
            // Вычитание дает оставшееся пространство, делить на два нужно чтобы это пространство было равномерно по дмум сторонам.
            $x = max($newW - $oldW, 0) / 2;
            $y = max($newH - $oldH, 0) / 2;

            // после поворота, геометрия страницы (page geometry) сбивается, поэтому нужно ставить преждние значения
            $image->setImagePage($newW, $newH,  0, 0);

            // обрезает не нужные края
            return $driver->crop(
                (int) $x,
                (int) $y,
                // min чтобы не выходило за границы изображения
                (int) min($newW, $oldW),
                (int) min($newH, $oldH)
            );
        }

        return $driver;
    }
}
