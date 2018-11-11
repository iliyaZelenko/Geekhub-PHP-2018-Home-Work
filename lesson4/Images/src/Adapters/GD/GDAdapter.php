<?php

namespace IlyaZelen\Adapters\GD;

use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Colors\AbstractColor;
use IlyaZelen\Colors\RGBAColor;
use IlyaZelen\Colors\UniversalColor;

class GDAdapter extends AbstractAdapter
{
    // специфичные конкретно для этого адаптера ошибки
    public const ERROR_ARGUMENT_WIDTH_OR_HEIGHT_AT_LEAST = 'Нужно указать хотя бы ширину или высоту.';
    public const ERROR_INVALID_DIMENSION = 'Минимальный размер — 2 пикселя';

    // каждый драйвер сам решает как ему инициализироватся с полученными настройками
//    public function __construct($settings = [])
//    {
//        parent::__construct($settings);
//    }

    public function getDriverName(): string
    {
        return 'GD';
    }

    public function getColor(string $value = null, array $options = [])
    {
        $image = $options['image'] ?? $this->image;

        // если null, то transparent
        if ($value === null) {
            $value = RGBAColor::getTransparentValue();
        }
        $color = UniversalColor::getColorInstanceByValue($value);

        // если требуется прозрачность
        if ($color->getFormat() === AbstractColor::RGBA_FORMAT) {
            // то есть используется rgba
            $RGBA = UniversalColor::getColorInstanceByValue($value);
            $RGBAArr = $RGBA->getRGBAArray();

            return imagecolorallocatealpha(
                $image, // каждый цвет привязывается к изображению
                // приходится так передавать, печально что в PHP нельзя как в JS вызвать
                // ...$RGBAArr и последним передать $opacity
                $RGBAArr[0], $RGBAArr[1], $RGBAArr[2],
                $RGBA->getOpacity(127) // конвертирует в 127 формат (127 - полная прозрачность, 0 - нет прозрачности)
            );
        }
        if ($color->getFormat() === AbstractColor::RGB_FORMAT) {
            $RGB = $color;
        } else {
            $RGB = $color->convertToRGB();
        }
        $RGBArr = $RGB->getRGBArray();

        // библиотека требует использование RGB формата чтобы задать цвет
        return imagecolorallocate($image, ...$RGBArr);
    }

    // создает прозрачное изображение
    public function createTransparentImage(int $width, int $height)
    {
        // в доке есть imagecreate но советуют imagecreatetruecolor, также работа с прозрачностью у них отличается
        $image = imagecreatetruecolor($width, $height);
        imagesavealpha($image, true);
        imagealphablending($image, false);

        $transparentColor = $this->getColor(
            RGBAColor::getTransparentValue(),
            [
                'image' => $image
            ]
        );
        imagefill($image, 0, 0, $transparentColor);

        return $image;
    }
}


