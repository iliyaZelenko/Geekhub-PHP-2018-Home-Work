<?php

namespace IlyaZelen\Colors;

class RGBColor extends ColorAbstract
{
    public function getFormat(): string
    {
        return static::RGB_FORMAT;
    }

    public function convertToHex(): HexColor
    {
        $RGBArr = $this->getRGBArray();
        $value = sprintf('#%02x%02x%02x', ...$RGBArr);

        return new HexColor($value);
    }

    public function convertToRGB(): ?RGBColor
    {
        $this->throwErrorConvertSameFormats();
    }

    public function convertToRGBA(): RGBAColor
    {
        [$r, $g, $b] = $this->getRGBArray();
        // просто добавляется alpha 1
        $value = "rgba($r, $g, $b, 1)";

        return new RGBAColor($value);
    }

    public function getRGBArray(): array
    {
        // TODO не зависеть от отступов
        return sscanf($this->getValue(), 'rgb(%d, %d, %d)');
    }
}
