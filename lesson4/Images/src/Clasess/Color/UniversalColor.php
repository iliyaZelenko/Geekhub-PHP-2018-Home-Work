<?php

namespace App\Clasess\Color;

// TODO возможно функционал из этого класса можно вынести в SuperImages или по другому сделать
class UniversalColor
{
    protected const ERROR_CONVERT_SAME_FORMATS = 'Этот формат не поддерживается';

    public static function getColorInstanceByValue($value)
    {
        $format = ColorAbstract::getFormatByValue($value);
        $instanceClass = static::getClassByFormat($format);

        return new $instanceClass($value);
    }

    public static function getClassByFormat($format): string
    {
        switch ($format) {
            case ColorAbstract::HEX_FORMAT:
                return HexColor::class;
            case ColorAbstract::RGB_FORMAT:
                return RGBColor::class;
            case ColorAbstract::RGBA_FORMAT:
                return RGBAColor::class;
            case ColorAbstract::NAME_FORMAT:
                return NameColor::class;
        }

        throw new \LogicException(static::ERROR_CONVERT_SAME_FORMATS);
    }
}
