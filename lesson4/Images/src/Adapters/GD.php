<?php

namespace App\Adapters;

use App\Clasess\Color\ColorAbstract;
use App\Clasess\Color\RGBAColor;
use App\Clasess\Color\UniversalColor;
use App\Clasess\FontMetric;
use App\Clasess\Size;
use function App\{getBoundaryDimension, getSuffixByFormat, getFont};

class GD extends AdapterAbstract
{
    // специфичные конкретно для этого адаптера ошибки
    protected const ERROR_ARGUMENT_WIDTH_OR_HEIGHT_AT_LEAST = 'Нужно указать хотя бы ширину или высоту.';
    protected const ERROR_INVALID_DIMENSION = 'Минимальный размер — 2 пикселя';


    public static function init($settings = [])
    {
        $fonts = $settings['fonts'] ?? [];

        foreach ($fonts as $name => $options) {
            // imageloadfont(
            static::$fonts[$name] = $options['path'];
        }
    }

    public function getColor(string $value = null, array $options = []) // , bool $transparent = false, float $opacity = 1
    {
        $image = $options['image'] ?? $this->image;

        // если null, то transparent
        if ($value === null) {
            $value = RGBAColor::getTransparentValue();
        }
        $color = UniversalColor::getColorInstanceByValue($value);

        // если требуется прозрачность
        if ($color->getFormat() === ColorAbstract::RGBA_FORMAT) {
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
        if ($color->getFormat() === ColorAbstract::RGB_FORMAT) {
            $RGB = $color;
        } else {
            $RGB = $color->convertToRGB();
        }
        $RGBArr = $RGB->getRGBArray();

        // библиотека требует использование RGB формата чтобы задать цвет
        return imagecolorallocate($image, ...$RGBArr);
    }

    public function new(int $width, int $height, string $backgroundColor = null): AdapterAbstract
    {
        $this->image = $this->createTransparentImage($width, $height);

        $color = $this->getColor($backgroundColor);
        imagefill($this->image, 0, 0, $color);

        return $this;
    }

    public function open(string $path): AdapterAbstract
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(static::UNABLE_TO_FIND_FILE);
        }

        // TODO возможно достаточно определять разширение в строке $path
        // $extension = substr(strrchr($path, '.'), 1);

        // mime тип файла в указанном пути
        $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);

        if (!$suffix = getSuffixByFormat($mime)) {
            throw new \InvalidArgumentException(static::ERROR_FORMAT_SUPPORT);
        }

        // вот так можно красиво вызывать ф-ю)
        $function = 'imagecreatefrom' . $suffix;
        // хоть можно и так ('imagecreatefrom' . $suffix)($path);
        $image = $function($path);

        // если вернуло false, то произошла ошибка
        if (!$image) {
            throw new \InvalidArgumentException(static::UNABLE_TO_DECODE_FILE);
        }

        $this->image = $image;

        return $this;
    }

    public function getSize(): Size
    {
        return new Size(
            imagesx($this->image),
            imagesy($this->image)
        );
    }

    public function crop(int $x, int $y, int $width, int $height): AdapterAbstract
    {
        [$originalW, $originalH] = $this->getSize()->getCompact();

        // чтобы не выходило за границы
        if ($x + $width > $originalW) {
            $width = $originalW - $x;
        }
        if ($y + $height > $originalH) {
            $height = $originalH - $y;
        }

        $this->image = imagecrop($this->image, [
            'x' => $x,
            'y' => $y,
            'width' => $width,
            'height' => $height
        ]);

        return $this;
    }

    public function output(string $format, int $compression = 65): string
    {
        $this->checkFormatSupport($format);
        // чтобы считывать вывод
        ob_start();
        { // скобки для красоты
            if (!$suffix = getSuffixByFormat($format)) {
                throw new \InvalidArgumentException(static::ERROR_FORMAT_SUPPORT);
            }

            // для gif не существует сжатия
            $params = [];
            if ($suffix === 'png') {
                // уровень сжатия от 0 до 9
                $params = [ceil($compression / 10) - 1];
            }
            if ($suffix === 'jpeg') {
                // уровень сжатия от 0 до 100
                $params = [$compression];
            }

            $function = 'image' . $suffix;
            // выводит данные изображения в буфер
            $function($this->image, null, ...$params);

            $buffer = ob_get_contents();
        }
        ob_end_clean();

        imagedestroy($this->image);

        return $buffer;
    }

    public function save(string $path): AdapterAbstract
    {
        $extension = substr(strrchr($path, '.'), 1);

        if (!$suffix = getSuffixByFormat($extension)) {
            throw new \InvalidArgumentException(static::ERROR_FORMAT_SUPPORT);
        }

        $function = 'image' . $suffix;
        $function($this->image, $path);

//        imagedestroy($this->image);

        return $this;
    }

    public function resize(int $newWidth = null, int $newHeight = null): AdapterAbstract
    {
        if ($newWidth === null && $newHeight === null) {
            throw new \InvalidArgumentException(static::ERROR_ARGUMENT_WIDTH_OR_HEIGHT_AT_LEAST);
        }
        // 1 пиксель тоже нельзя
        if (($newWidth !== null && $newWidth < 2) || ($newHeight !== null && $newHeight < 2)) {
            throw new \InvalidArgumentException(static::ERROR_INVALID_DIMENSION);
        }

        [$widthOrig, $heightOrig] = $this->getSize()->getCompact();

        // по сути $heightRatio и $widthRatio ниже можно заменить на это (где указанно null подсчитается как 0, поэтому выберется сторона которая указанна в параметре)
        // ratio это показатель на сколько изменится картинка в размере, например 1.1 — новый размер будет 110% старого, 0.5 — 50% старого
        $ratio = max($newHeight / $heightOrig, $newWidth / $widthOrig);

        // если не указана новая ширина, то подсчитывает ее относительно текущей ширины, умножая ее на соотношение
        // новой и старой высоты
        if ($newWidth === null) {
            // $heightRatio = $newHeight / $heightOrig;
            $newWidth = $widthOrig * $ratio;
            // в JS можно было не писать if, а сделать так: $newWidth = $newWidth || $widthOrig * $ratio; но в PHP я так понял возвращает bool от логических операторов
        }
        // так же подсчитывает новую высоту
        if ($newHeight === null) {
            // $widthRatio = $newWidth / $widthOrig;
            $newHeight = $heightOrig * $ratio;
        }
        // куда будет произведенна копия
        $newImage = $this->createTransparentImage($newWidth, $newHeight);

//        $white = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
//        imagefill($newImage, 0, 0, $white);


        // копирует с измененный оригинал на $newImage
        imagecopyresized( // или imagecopyresampled
            $newImage, $this->image,
            // координаты начала $newImage и потом $this->image
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $widthOrig, $heightOrig
        );
        $this->image = $newImage;

        return $this;
    }

    public function fit(int $boundaryWidth = null, int $boundaryHeight = null): AdapterAbstract
    {
        // таким образом не обязательно указывать каждый из аргументов
        if ($boundaryWidth === null) $boundaryWidth = 9999999;
        if ($boundaryHeight === null) $boundaryHeight = 9999999;

        [$width, $height] = $this->getSize()->getCompact();

        [$newWidth, $newHeight] = getBoundaryDimension(
            $boundaryWidth,
            $boundaryHeight,
            $width,
            $height
        );

        return $this->resize($newWidth, $newHeight);
    }

    public function rotate($angle, $backgroundColor = null, $crop = false): AdapterAbstract
    {
        // в GD указывается против часовой стрелки, получает значение по часовой стрелке
        $angle = 360 - $angle;

        // определяет цвет
        if ($backgroundColor === null) {
            $colorValue = RGBAColor::getTransparentValue();
        } else {
            $colorValue = $backgroundColor;
        }
        $color = $this->getColor($colorValue);

        // если нужно обрезать в конце, то запоминает размеры до поворота
        if ($crop) {
            [$oldW, $oldH] = $this->getSize()->getCompact();
        }

        $this->image = imagerotate($this->image, $angle, $color);

        // обрезает края
        if ($crop) {
            [$newW, $newH] = $this->getSize()->getCompact();

            // Координаты начала обрезки.
            // Новая длинна/высота может быть меньше старой, поэтому меньше нуля не нужно брать.
            // Вычитание дает оставшееся пространство, делить на два нужно чтобы это пространство было равномерно по дмум сторонам.
            $x = max($newW - $oldW, 0) / 2;
            $y = max($newH - $oldH, 0) / 2;

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

    // в GD рисует немного криво рамку если она повернутая
    public function border($color = 'black', $thickness = 1): AdapterAbstract
    {
        $x1 = 0;
        $y1 = 0;
        $x2 = imagesx($this->image) - 1;
        $y2 = imagesy($this->image) - 1;

        for ($i = 0; $i < $thickness; $i++) {
            // координаты начала и конца идут в центр благодрая их инкременту и декременту
            imagerectangle(
                $this->image,
                $x1++, $y1++,
                $x2--, $y2--,
                $this->getColor($color)
            );
        }

        return $this;
    }

    public function flip($mode = 'vertical'): AdapterAbstract
    {
        if ($mode === 'vertical') {
            imageflip($this->image, IMG_FLIP_VERTICAL);
        }
        if ($mode === 'horizontal') {
            imageflip($this->image, IMG_FLIP_HORIZONTAL);
        }
        if ($mode === 'both') {
            imageflip($this->image, IMG_FLIP_BOTH);
        }

        return $this;
    }

    // заметил что в GD немного больше шрифт чем у Imagick при одинаковых $size
    public function text(string $text, $x = 0, $y = 0, string $color = 'black', string $font = null, int $size = 20, int $angle = 0)
    {
        $color = $this->getColor($color);
        $font = static::getFont($font);

        imagettftext(
            $this->image,
            $size,
            $angle,
            $x,
            $y,
            $color,
            $font,
            $text
        );

        return $this;
    }

    public static function queryFontMetrics(string $text, int $size, string $font = null, int $angle = 0): FontMetric
    {
        $font = static::getFont($font);

        $data = imagettfbbox($size, $angle, $font, $text);

        return new FontMetric([
            'textWidth' => $data[2] - $data[0],
            'textHeight' => $data[1] - $data[5],
            'original' => $data
        ]);
    }

    // создает прозрачное изображение
    protected function createTransparentImage(int $width, int $height)
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

    protected static function getFont($font)
    {
        return getFont($font, static::$fonts, static::class);

    }
}


