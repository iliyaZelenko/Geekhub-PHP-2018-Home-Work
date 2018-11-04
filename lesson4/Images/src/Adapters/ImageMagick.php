<?php

namespace App\Adapters;

use App\Clasess\Color\RGBAColor;
use App\Clasess\FontMetric;
use App\Clasess\Size;
use function App\{getFont};

class ImageMagick extends AdapterAbstract
{
    public static function init($settings = [])
    {
        $fonts = $settings['fonts'] ?? [];

        foreach ($fonts as $name => $options) {
            static::$fonts[$name] = $options['path'];
        }
    }

    public function getColor(string $value = null): \ImagickPixel
    {
        // если null, то transparent
        if ($value === null) {
            $value = RGBAColor::getTransparentValue();
        }

        return new \ImagickPixel($value);
    }

    public function new(int $width, int $height, string $backgroundColor = null): AdapterAbstract
    {
        $color = $this->getColor($backgroundColor);

        $this->image = new \Imagick();
        $this->image->newImage($width, $height, $color); // , 'png'

        return $this;
    }

    public function open(string $path): AdapterAbstract
    {
        $this->image = new \Imagick($path);

        return $this;
    }

    public function getSize(): Size
    {
        $size = $this->image->getImageGeometry();

        return new Size(
            $size['width'],
            $size['height']
        );
    }

    public function crop(int $x, int $y, int $width, int $height): AdapterAbstract
    {
        $this->image->cropImage($width, $height, $x, $y);

        return $this;
    }

    // TODO $format Abstract
    public function output(string $format, int $compression = 65): string
    {
        $this->checkFormatSupport($format);

        $this->image->setImageFormat($format);
        $this->image->setCompressionQuality($compression);
        // Почему-то этих функций очень много:
//        $this->image->setCompression($compression);
//        $this->image->setImageCompression($compression);
//        $this->image->setImageCompressionQuality($compression);

        return $this->image->getImagesBlob();
    }

    public function save(string $path): AdapterAbstract
    {
        $this->image->writeImage($path);
//        $this->image->destroy();

        return $this;
    }

    public function resize(int $newWidth = null, int $newHeight = null): AdapterAbstract
    {
        if ($newWidth === null) $newWidth = 0;
        if ($newHeight === null) $newHeight = 0;

        $this->image->adaptiveResizeImage($newWidth, $newHeight);

        return $this;
    }

    public function fit(int $boundaryWidth = null, int $boundaryHeight = null): AdapterAbstract
    {
        // таким образом не обязательно указывать каждый из аргументов
        if ($boundaryWidth === null) $boundaryWidth = 9999999;
        if ($boundaryHeight === null) $boundaryHeight = 9999999;

        $this->image->adaptiveResizeImage($boundaryWidth, $boundaryHeight, true);

        return $this;
    }

    public function rotate($angle, $backgroundColor = null, $crop = false): AdapterAbstract
    {
        // цвет пустого фона
        $color = $this->getColor($backgroundColor);

        // если нужно обрезать в конце, то запоминает размеры до поворота
        if ($crop) {
            [$oldW, $oldH] = $this->getSize()->getCompact();
        }

        $this->image->rotateimage($color, $angle);

        // обрезает края
        if ($crop) {
            [$newW, $newH] = $this->getSize()->getCompact();

            // Координаты начала обрезки.
            // Новая длинна/высота может быть меньше старой, поэтому меньше нуля не нужно брать.
            // Вычитание дает оставшееся пространство, делить на два нужно чтобы это пространство было равномерно по дмум сторонам.
            $x = max($newW - $oldW, 0) / 2;
            $y = max($newH - $oldH, 0) / 2;

            // после поворота, геометрия страницы (page geometry) сбивается, поэтому нужно ставить преждние значения
            $this->image->setImagePage($newW, $newH,  0, 0);

            // обрезает не нужные края
            return $this->crop(
                $x,
                $y,
                // min чтобы не выходило за границы изображения
                min($newW, $oldW),
                min($newH, $oldH)
            );
        }

        return $this;
    }

    public function border($color = 'black', $thickness = 1): AdapterAbstract
    {
        $this->image->borderImage($this->getColor($color), $thickness, $thickness);

        return $this;
    }

    public function flip($mode = 'vertical'): AdapterAbstract
    {
        if ($mode === 'vertical') {
            $this->image->flipImage();
        }
        if ($mode === 'horizontal') {
            $this->image->flopImage();
        }
        if ($mode === 'both') {
            $this->image->flipImage();
            $this->image->flopImage();
        }

        return $this;
    }

    public function text(string $text, $x = 0, $y = 0, string $color = 'black', string $font = null, int $size = 20, int $angle = 0)
    {
        $color = $this->getColor($color);
        $font = static::getFont($font);

        $draw = new \ImagickDraw();
        $draw->setFillColor($color);
        $draw->setFont($font);
        $draw->setFontSize($size);

        // TODO abstract, есть стандартные поля, но они могут быть не обязательны, а есть поле в котором все оригинальные настройки
//        var_dump($this->image->queryFontMetrics($draw, "Hello World!"));

        $this->image->annotateImage(
            $draw,
            $x,
            $y,
            $angle,
            $text
        );

        return $this;
    }

    // TODO Object
    public static function queryFontMetrics(string $text, int $size, string $font = null, int $angle = 0): FontMetric
    {
        $font = static::getFont($font);

        $emptyImage = new \Imagick();
        $draw = new \ImagickDraw();
        $draw->setFont($font);
        $draw->setFontSize($size);

        $data = $emptyImage->queryFontMetrics($draw, $text);

        return new FontMetric([
            'textWidth' => $data['textWidth'],
            'textHeight' => $data['textHeight'],
            'original' => $data
        ]);
    }

    // TODO тот же Object
//    protected static function queryFontMetricsByDraw(string $text, \ImagickDraw $draw, int $angle = 0)
//    {
//        $emptyImage = new Imagick();
//
//        $font = $draw->getFont();
//        $size = $draw->getFontSize();
//        \Imagick::queryFontMetrics();
//
//        // печально что этот метод не
//        return $emptyImage->queryFontMetrics($text, $size, $font); // , $angle не используется всеравно
//    }

    protected static function getFont($font)
    {
        return getFont($font, static::$fonts, static::class);
    }
}
