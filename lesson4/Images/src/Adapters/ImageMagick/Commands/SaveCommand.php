<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\SaveArgumentsTrait;

// Сохраняет изображение по указанному пути.
class SaveCommand extends AbstractCommand
{
    use SaveArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @return void
     */
    public function execute(&$image, AbstractAdapter $driver): void
    {
        $this->setArguments();

        $image->writeImage(
            $this->argument('path')
        );
    }
}
