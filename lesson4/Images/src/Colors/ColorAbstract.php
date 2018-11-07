<?php

namespace IlyaZelen\Colors;

abstract class ColorAbstract
{
    public const HEX_FORMAT = 'hex';
    public const NAME_FORMAT = 'name';
    public const RGB_FORMAT = 'rgb';
    public const RGBA_FORMAT = 'rgba';
    protected const ERROR_CONVERT_SAME_FORMATS = 'Исходный формат и из параметра совпадают.';
    protected const ERROR_CANT_CONVERT_FORMAT_TO_NAME_FORMAT = 'Не удалось конвертировать формат в именной формат: нет такого имени цвета.';
    protected const ERROR_NO_SUPPORT_FORMAT = 'Библиотека не поддерживает данный формат.';
    protected const ERROR_INVALID_FORMAT = 'Не правильный формат.';

    abstract public function getFormat();
    abstract public function convertToHex(): ?HexColor;
    abstract public function convertToRGB(): ?RGBColor;
    abstract public function convertToRGBA(): ?RGBAColor;

    protected $value;


    public function __construct($value)
    {
        if (!$format = static::getFormatByValue($value)) {
            throw new \InvalidArgumentException(static::ERROR_NO_SUPPORT_FORMAT);
        }
        if ($format !== $this->getFormat()) {
            throw new \InvalidArgumentException(static::ERROR_INVALID_FORMAT);
        }

        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function convertTo($convertFormat)
    {
        $currentFormat = $this->getFormat();

        if ($convertFormat === $currentFormat) {
            // TODO подумать как луче
            throw new \LogicException(static::ERROR_CONVERT_SAME_FORMATS);
//            return $this;
        }
//        if ($convertFormat === static::NAME_FORMAT) {
//            return $this->convertFromFormatToName($currentFormat);
//        }

        switch ($convertFormat) {
            case static::HEX_FORMAT:
                return $this->convertToHex();

            case static::RGB_FORMAT:
                return $this->convertToRGB();

            case static::RGBA_FORMAT:
                return $this->convertToRGBA();

            case static::NAME_FORMAT:
                return $this->convertFromFormatToName($currentFormat);
        }

        throw new \LogicException(static::ERROR_NO_SUPPORT_FORMAT);
    }

    public function convertToName(): NameColor
    {
        $currentFormat = $this->getFormat();

        if ($currentFormat === static::NAME_FORMAT) {
            throw new \LogicException(static::ERROR_CONVERT_SAME_FORMATS);
        }
        if (!$value = $this->getFormatValueFromName($currentFormat)) {
            throw new \LogicException(static::ERROR_CANT_CONVERT_FORMAT_TO_NAME_FORMAT);
        }

        return new NameColor($value);
    }

    public static function getFormatByValue($value): string
    {
        if (strpos($value, '#') !== false) {
            return static::HEX_FORMAT;
        }
        if (strpos($value, 'rgba') !== false) {
            return static::RGBA_FORMAT;
        }
        if (strpos($value, 'rgb') !== false) {
            return static::RGB_FORMAT;
        }

        // TODO check value in name values
        return static::NAME_FORMAT;
    }



    protected function getFormatValueFromName($format): ?string
    {
        $value = $this->getValue();

        // если идет работа с форматом RGBA, то он преобразовывается в RGB и идет работа с ним
        if ($this->getFormat() === static::RGBA_FORMAT) {
            // или $this->convertToRGB()->getValue()
            $value = (new RGBAColor($value))->convertToRGB()->getValue();
            $format = static::RGB_FORMAT;
        }

        foreach (NameColor::getNames() as $name => $formats) {
            if ($formats[$format] === $value) {
                return $name;
            }
        }

        return null;
    }

    protected function throwErrorConvertSameFormats(): void
    {
        throw new \LogicException(static::ERROR_CONVERT_SAME_FORMATS);
    }
}
