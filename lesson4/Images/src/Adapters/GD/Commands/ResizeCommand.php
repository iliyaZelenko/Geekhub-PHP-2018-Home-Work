<?php

namespace IlyaZelen\Adapters\GD\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\ResizeArgumentsTrait;
use IlyaZelen\Adapters\GD\GDAdapter;

/**
 * Меняет размер. Если не указанна одна из сторон, то не указанная строна подсчитается по соотношению
 * старой указанной стороны к новой, в результате не указанная сторона будет пропорциональна указанной (их соотношение не изменится).
 */
class ResizeCommand extends AbstractCommand
{
    use ResizeArgumentsTrait;

    /**
     * @param $image
     * @param AbstractAdapter $driver
     */
    public function execute(&$image, AbstractAdapter $driver): void
    {
        $this->setArguments();

        $newWidth = $this->argument('newWidth');
        $newHeight = $this->argument('newHeight');

        if ($newWidth === null && $newHeight === null) {
            throw new \InvalidArgumentException(GDAdapter::ERROR_ARGUMENT_WIDTH_OR_HEIGHT_AT_LEAST);
        }
        // 1 пиксель тоже нельзя
        if (($newWidth !== null && $newWidth < 2) || ($newHeight !== null && $newHeight < 2)) {
            throw new \InvalidArgumentException(GDAdapter::ERROR_INVALID_DIMENSION);
        }

        [$widthOrig, $heightOrig] = $driver->getSize()->getCompact();

        // по сути $heightRatio и $widthRatio ниже можно заменить на это (где указанно null подсчитается как 0, поэтому выберется сторона которая указанна в параметре)
        // ratio это показатель на сколько изменится картинка в размере, например 1.1 — новый размер будет 110% старого, 0.5 — 50% старого
        $ratio = max($newHeight / $heightOrig, $newWidth / $widthOrig);

        // если не указана новая ширина, то подсчитывает ее относительно текущей ширины, умножая ее на соотношение
        // новой и старой высоты
        if ($newWidth === null) {
            // $heightRatio = $newHeight / $heightOrig;
            $newWidth = $widthOrig * $ratio;
            // в JS можно было не писать if, а сделать так: $newWidth = $newWidth || $widthOrig * $ratio; но в PHP я так понял возвращает bool от логических операторов
        }
        // так же подсчитывает новую высоту
        if ($newHeight === null) {
            // $widthRatio = $newWidth / $widthOrig;
            $newHeight = $heightOrig * $ratio;
        }
        // куда будет произведенна копия
        $newImage = $driver->createTransparentImage($newWidth, $newHeight);


        // копирует с измененный оригинал на $newImage
        imagecopyresized( // или imagecopyresampled
            $newImage, $image,
            // координаты начала $newImage и потом $image
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $widthOrig, $heightOrig
        );

        $image = $newImage;
    }
}
