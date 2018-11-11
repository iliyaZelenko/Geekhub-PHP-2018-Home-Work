<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\FitArgumentsTrait;

// Рамка в которую должно поместиться изображение. Можно указать и только одну величину, не указанная будет проигнорированна.
class FitCommand extends AbstractCommand
{
    use FitArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @return void
     * @throws \ImagickException
     */
    public function execute(&$image, AbstractAdapter $driver): void
    {
        $this->setArguments();

        $boundaryWidth = $this->argument('boundaryWidth');
        $boundaryHeight = $this->argument('boundaryHeight');

        // таким образом не обязательно указывать каждый из аргументов
        if ($boundaryWidth === null) $boundaryWidth = 9999999;
        if ($boundaryHeight === null) $boundaryHeight = 9999999;

        $image->adaptiveResizeImage($boundaryWidth, $boundaryHeight, true);
    }
}
