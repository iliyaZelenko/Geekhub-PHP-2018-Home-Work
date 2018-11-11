<?php

namespace IlyaZelen\Adapters;

use IlyaZelen\FontMetric;
use IlyaZelen\Size;
use IlyaZelen\SuperImages;
use function IlyaZelen\{getFont};

/**
 * Вот так можно генерировать методы которые вызываются через __call
 *
 * @method Size getSize();
 * @method AbstractAdapter new(int $width, int $height, string $backgroundColor = null);
 * @method AbstractAdapter crop(int $x, int $y, int $width, int $height);
 * @method string output(string $format, int $compression = 65);
 * @method AbstractAdapter save(string $path);
 * @method AbstractAdapter resize(int $newWidth = null, int $newHeight = null);
 * @method AbstractAdapter fit(int $boundaryWidth = null, int $boundaryHeight = null);
 * @method AbstractAdapter rotate(int $angle = null, int $backgroundColor = null, $crop = false);
 * @method AbstractAdapter border($color = 'black', $thickness = 1);
 * @method AbstractAdapter flip($mode = 'vertical');
 * @method AbstractAdapter text(string $text, $x = 0, $y = 0, string $color = 'black', string $font = null, int $size = 20, int $angle = 0);
 * @method FontMetric queryFontMetrics(string $text, int $size, string $font = null, int $angle = 0);
 */
abstract class AbstractAdapter implements AdapterInterface
{
    public const ERROR_INVALID_DRIVER = 'Переданный драйвер не поддерживается.';
    public const ERROR_FORMAT_SUPPORT = 'Этот формат не поддерживается.';
    public const ERROR_COMMAND_SUPPORT = 'Команда %s не поддерживается драйвером %s.';
    public const UNABLE_TO_FIND_FILE = 'Не удалось найти файл.';
    public const UNABLE_TO_DECODE_FILE = 'Не удалосб декодировать файл.';
    // эти константы получаются из хелпера getFont
    public const ERROR_NO_FONTS = 'У вас не указанны шрифты.';
    public const ERROR_NO_FONT_EXISTS = 'У вас не указан этот шрифт.';

    // текущее изображение
    protected $image;
    // установленные шрифты
    protected $fonts = [];

    abstract public function getDriverName(): string;
    // возвращает сущность цвета с которой работает библиотека
    abstract public function getColor(string $value);


    public function __construct($settings = [])
    {
        $fonts = $settings['fonts'] ?? [];

        foreach ($fonts as $name => $options) {
            $this->fonts[$name] = $options['path'];
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return AbstractAdapter|mixed
     */
    public function __call($name, $arguments)
    {
        $result = $this->executeCommand($name, $arguments);

        return $result ?? $this;
    }

    public function executeCommand($name, $arguments)
    {
        $commandName = $this->getCommandClassName($name);
        $command = new $commandName($arguments);

        return $command->execute($this->image, $this);
    }

    // паттерн Factory Method
    public static function factory($driverName, $settings = []): AbstractAdapter
    {
        if (!isset(SuperImages::$drivers[$driverName])) {
            throw new \InvalidArgumentException(static::ERROR_INVALID_DRIVER);
        }

        // возвращает инициализированный объект драйвера
        return new SuperImages::$drivers[$driverName]($settings);
    }

    public function checkFormatSupport($format): void
    {
        if (!\in_array($format, SuperImages::$supportedFormats, true) ) {
            throw new \InvalidArgumentException(static::ERROR_FORMAT_SUPPORT);
        }
    }

    public function getFont($font)
    {
        return getFont($font, $this->fonts, static::class);
    }

    protected function getCommandClassName($name)
    {
//        $name = mb_convert_case($name[0], MB_CASE_UPPER, 'utf-8') . mb_substr($name, 1, mb_strlen($name));

        $drivername = $this->getDriverName();
        $class = sprintf('\IlyaZelen\Adapters\%s\Commands\%sCommand', $drivername, ucfirst($name));

        if (class_exists($class)) {
            return $class;
        }

        throw new \InvalidArgumentException(
            sprintf(static::ERROR_COMMAND_SUPPORT, $name, $drivername)
        );
    }
}
