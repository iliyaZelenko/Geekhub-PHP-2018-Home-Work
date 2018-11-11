<?php

namespace IlyaZelen\Adapters\GD\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Size;

// Возвращает размер изображения в пикселях.
class GetSizeCommand extends AbstractCommand
{
    public function execute(&$image, AbstractAdapter $driver): Size
    {
        return new Size(
            imagesx($image),
            imagesy($image)
        );
    }
}
