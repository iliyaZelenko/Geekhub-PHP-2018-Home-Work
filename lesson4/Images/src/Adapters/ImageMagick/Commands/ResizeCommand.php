<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\ResizeArgumentsTrait;

/**
 * Меняет размер. Если не указанна одна из сторон, то не указанная строна подсчитается по соотношению
 * старой указанной стороны к новой, в результате не указанная сторона будет пропорциональна указанной (их соотношение не изменится).
 */
class ResizeCommand extends AbstractCommand
{
    use ResizeArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @throws \ImagickException
     */
    public function execute(&$image, AbstractAdapter $driver): void
    {
        $this->setArguments();

        $newWidth = $this->argument('newWidth');
        $newHeight = $this->argument('newHeight');

        if ($newWidth === null) $newWidth = 0;
        if ($newHeight === null) $newHeight = 0;

        $image->adaptiveResizeImage($newWidth, $newHeight);
    }
}
