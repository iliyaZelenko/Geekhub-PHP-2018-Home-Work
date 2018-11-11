<?php

namespace IlyaZelen\Colors;

// TODO возможно функционал из этого класса можно вынести в SuperImages или по другому сделать
class UniversalColor
{
    protected const ERROR_NO_SUPPORT_FORMAT = 'Библиотека не поддерживает данный формат цвета.';

    public static function getColorInstanceByValue($value)
    {
        $format = AbstractColor::getFormatByValue($value);
        $instanceClass = static::getClassByFormat($format);

        return new $instanceClass($value);
    }

    public static function getClassByFormat($format): string
    {
        switch ($format) {
            case AbstractColor::HEX_FORMAT:
                return HexColor::class;
            case AbstractColor::RGB_FORMAT:
                return RGBColor::class;
            case AbstractColor::RGBA_FORMAT:
                return RGBAColor::class;
            case AbstractColor::NAME_FORMAT:
                return NameColor::class;
        }

        throw new \LogicException(static::ERROR_NO_SUPPORT_FORMAT);
    }
}
