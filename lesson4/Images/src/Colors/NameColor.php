<?php

namespace IlyaZelen\Colors;

class NameColor extends ColorAbstract
{
    public function getFormat(): string
    {
        return static::NAME_FORMAT;
    }

    public function convertToRGB(): RGBColor
    {
//        $RGBStr = $this->otherFormatValue(static::RGB_FORMAT);
//
//        return new RGBColor($RGBStr);

        // почему PhpStrom выдает предупреждение? все же логично)
        return $this->getOtherFormatInstance(RGBColor::class);
    }

    public function convertToRGBA(): RGBAColor
    {
        return $this->convertToRGB()->convertToRGBA();
    }

    public function convertToHex(): HexColor
    {
//        $HexStr = $this->otherFormatValue(static::HEX_FORMAT);
//
//        return new HexColor($HexStr);
        return $this->getOtherFormatInstance(HexColor::class);
    }

    public static function getNames(): array
    {
        // сейчас поддерживается 6 именованных цвета
        return [
            'red' => [
                static::RGB_FORMAT => 'rgb(255, 0, 0)',
                static::HEX_FORMAT => '#FF0000'
            ],
            'green' => [
                static::RGB_FORMAT => 'rgb(0, 128, 0)',
                static::HEX_FORMAT => '#008000'
            ],
            'blue' => [
                static::RGB_FORMAT => 'rgb(0, 0, 255)',
                static::HEX_FORMAT => '#0000FF'
            ],
            'black' => [
                static::RGB_FORMAT => 'rgb(0, 0, 0)',
                static::HEX_FORMAT => '#000000'
            ],
            'white' => [
                static::RGB_FORMAT => 'rgb(255, 255, 255)',
                static::HEX_FORMAT => '#FFFFFF'
            ],
            'yellow' => [
                static::RGB_FORMAT => 'rgb(255, 255, 0)',
                static::HEX_FORMAT => '#FFFF00'
            ]
        ];
    }

    protected function otherFormatValue($format)
    {
        return static::getNames()[$this->value][$format];
    }

    protected function getOtherFormatInstance($class): ColorAbstract
    {
        $value = static::getNames()[$this->value][$class::getFormat()];

        return new $class($value);
    }
}
