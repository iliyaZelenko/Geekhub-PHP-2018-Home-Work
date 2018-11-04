<?php

namespace App\Clasess\Color;

// В этой библиотеке (SuperImages), принято обозначать RGBA в формата как в CSS: rgba(<red>, <green>, <blue>, <alpha>)
class RGBAColor extends ColorAbstract
{
    public function getFormat(): string
    {
        return static::RGBA_FORMAT;
    }

    public function convertToHex(): HexColor
    {
        $RGBAArr = $this->getRGBAArray();
        $value = sprintf('#%02x%02x%02x', ...$RGBAArr);

        return new HexColor($value);
    }

    public function convertToRGB(): RGBColor
    {
        [$r, $g, $b] = $this->getRGBAArray();

        return new RGBColor("rgb($r, $g, $b)");
    }

    public function convertToRGBA(): ?RGBAColor
    {
        $this->throwErrorConvertSameFormats();
    }

    public function getRGBAArray(): array
    {
        // TODO не зависеть от отступов
        return sscanf($this->getValue(), 'rgba(%d, %d, %d, %f)');
    }

    // Alpha в $this->getValue() в формате от 0 до единицы, где 0 - полностью прозрачный, 1 - полностью не прозрачный.
    // Но в некоторых системах Alpha может потребоватся конвертировать в 255 (обычно для 32 битной системы), иногда в 127 (как в PHP/GD библиотеке),
    // но 127 на самом деле странное значение, вместо него обязано было быть 255, насколько понял эо ошибка GD разработчиков
    public function getOpacity($convertFormat = null, $sourceFormat = 1): float
    {
        $RGBAArr = $this->getRGBAArray();
        $alpha = (float) end($RGBAArr);

        if ($convertFormat === null) {
            return $alpha;
        }

        // из 0...1 формата в 0...127 формат
        if ($convertFormat === 127 && $sourceFormat === 1) {
            // 0 === 127
            // 1 === 0
            // 0.5 === 127 / 2
            // 0.25 === 127 / 4
            return abs($alpha - 1) * 127;
        }
        // из 0...1 формата в 0...255 формат
        if ($convertFormat === 255 && $sourceFormat === 1) {
            return abs($alpha - 1) * 255;
        }

        // например, чтобы rgba(0, 0, 0, 63.5) конвертировтаь в rgba(0, 0, 0, 0.5)
        if ($convertFormat === 1 && $sourceFormat === 127) {
            $alpha = (127 - $alpha) / 127.0;
        }
        // из 255 в 1
        if ($convertFormat === 1 && $sourceFormat === 255) {
            $alpha = (255 - $alpha) / 255.0;
        }

        // из 255 в 127
        if ($convertFormat === 127 && $sourceFormat === 255) {
            $alpha = $alpha / 127 * 255;
        }
        // из 127 в 255
        if ($convertFormat === 255 && $sourceFormat === 127) {
            $alpha = $alpha / 255 * 127;
        }

        return $alpha;
    }

    // прозрачный цвет
    public static function getTransparentValue(): string
    {
        return 'rgba(0, 0, 0, 0)';
    }

//    public static function getTransparentInstance(): RGBAColor
//    {
//        return new static(static::getTransparentValue());
//    }
}
