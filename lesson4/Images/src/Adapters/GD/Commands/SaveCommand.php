<?php

namespace IlyaZelen\Adapters\GD\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\SaveArgumentsTrait;
use function IlyaZelen\{getSuffixByFormat};

// Сохраняет изображение по указанному пути.
class SaveCommand extends AbstractCommand
{
    use SaveArgumentsTrait;

    /**
     * @param $image
     * @param AbstractAdapter $driver
     * @return void
     */
    public function execute(&$image, AbstractAdapter $driver): void
    {
        $this->setArguments();

        $path = $this->argument('path');


        $extension = substr(strrchr($path, '.'), 1);

        if (!$suffix = getSuffixByFormat($extension)) {
            throw new \InvalidArgumentException(AbstractAdapter::ERROR_FORMAT_SUPPORT);
        }

        $function = 'image' . $suffix;
        $function($image, $path);

//        imagedestroy($this->image);
    }
}
