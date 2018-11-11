<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\OutputArgumentsTrait;

// Возвращает изображение в строковом виде.
class OutputCommand extends AbstractCommand
{
    use OutputArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @return string
     * @throws \ImagickException
     */
    public function execute(&$image, AbstractAdapter $driver): string
    {
        $this->setArguments();

        $format = $this->argument('format');
        $compression = $this->argument('compression');


        $driver->checkFormatSupport($format);

        $image->setImageFormat($format);
        $image->setCompressionQuality($compression);
        // Почему-то этих функций очень много:
//        $this->image->setCompression($compression);
//        $this->image->setImageCompression($compression);
//        $this->image->setImageCompressionQuality($compression);

        return $image->getImagesBlob();
    }
}
