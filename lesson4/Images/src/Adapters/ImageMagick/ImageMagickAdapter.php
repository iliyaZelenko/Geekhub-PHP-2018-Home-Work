<?php

namespace IlyaZelen\Adapters\ImageMagick;

use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Colors\RGBAColor;
use IlyaZelen\FontMetric;

/**
 * Уточняет тип чтобы ide не писала что не известный метод у свойства.
 * @property \Imagick $image
 */
class ImageMagickAdapter extends AbstractAdapter
{
//    public function __construct($settings = [])
//    {
//        parent::__construct($settings);
//    }

    public function getDriverName(): string
    {
        return 'ImageMagick';
    }

    public function getColor(string $value = null): \ImagickPixel
    {
        // если null, то transparent
        if ($value === null) {
            $value = RGBAColor::getTransparentValue();
        }

        return new \ImagickPixel($value);
    }
}
