<?php

namespace App\Clasess\Color;

class HexColor extends ColorAbstract
{
    public function getFormat(): string
    {
        return static::HEX_FORMAT;
    }

    public function convertToRGB(): RGBColor
    {
        // TODO более надежный способ: https://stackoverflow.com/a/39627637/5286034
        [$r, $g, $b] = sscanf($this->value, '#%02x%02x%02x');

        return new RGBColor("rgb($r, $g, $b)");
    }

    public function convertToHex(): ?HexColor
    {
        $this->throwErrorConvertSameFormats();
    }

    public function convertToRGBA(): ?RGBAColor
    {
        return $this->convertToRGB()->convertToRGBA();
    }
}
