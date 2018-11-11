<?php

namespace IlyaZelen\Adapters\GD\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\FitArgumentsTrait;
use function IlyaZelen\{getBoundaryDimension};

// Рамка в которую должно поместиться изображение. Можно указать и только одну величину, не указанная будет проигнорированна.
class FitCommand extends AbstractCommand
{
    use FitArgumentsTrait;

    /**
     * @param $image
     * @param AbstractAdapter $driver
     * @return AbstractAdapter
     */
    public function execute(&$image, AbstractAdapter $driver)
    {
        $this->setArguments();

        $boundaryWidth = $this->argument('boundaryWidth');
        $boundaryHeight = $this->argument('boundaryHeight');

        // таким образом не обязательно указывать каждый из аргументов
        if ($boundaryWidth === null) $boundaryWidth = 9999999;
        if ($boundaryHeight === null) $boundaryHeight = 9999999;

        [$width, $height] = $driver->getSize()->getCompact();

        [$newWidth, $newHeight] = getBoundaryDimension(
            $boundaryWidth,
            $boundaryHeight,
            $width,
            $height
        );

        return $driver->resize($newWidth, $newHeight);
    }
}
