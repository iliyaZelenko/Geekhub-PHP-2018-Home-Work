<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Size;

// Возвращает размер изображения в пикселях.
class GetSizeCommand extends AbstractCommand
{
    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @return Size
     */
    public function execute(&$image, AbstractAdapter $driver): Size
    {
        $size = $image->getImageGeometry();

        return new Size(
            $size['width'],
            $size['height']
        );
    }
}
