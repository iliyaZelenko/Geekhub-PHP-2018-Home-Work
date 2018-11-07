<?php

namespace IlyaZelen;

use IlyaZelen\Adapters\{AdapterAbstract, GD, ImageMagick};
use IlyaZelen\Clasess\FontMetric;

class SuperImages
{
    protected const ERROR_INVALID_DRIVER = 'Переданный драйвер не поддерживается.';

    protected $drivers = [
        'GD' => GD::class,
        'ImageMagick' => ImageMagick::class
    ];

    // какой класс драйвера картинок используется из доступных, ставится при инициализации
    protected $driver;
    // настройки выбранного драйвера
    protected $driverSettings;

    // поддерживаемые форматы (GD ограничивает)
    public static $supportedFormats = ['png', 'jpg', 'jpeg', 'gif'];


    // ставит используемый драйвер
    public function __construct(string $driver = 'GD', $settings = [])
    {
        if (!isset($this->drivers[$driver])) {
            throw new \InvalidArgumentException(static::ERROR_INVALID_DRIVER);
        }

        // настройки выбранного драйвера
        $this->driverSettings = $settings['driverSettings'] ?? [];

        $this->driver = $this->drivers[$driver];
        $this->initDriver();
    }

    // Варианты взаимодействия с адаптерами через которые создаются объекты адаптеров и дальше уже с ними  можно работать.
    public function new(...$arg): AdapterAbstract
    {
        return $this->newDriverInstanceAndCall('new', $arg);
    }
    public function open(...$arg): AdapterAbstract
    {
        return $this->newDriverInstanceAndCall('open', $arg);
    }

    public function queryFontMetrics(...$arg): FontMetric
    {
        return $this->driver::queryFontMetrics(...$arg);
    }

    // инициализация драйвера которая проходит один раз на этапе инициализации библиотеки
    protected function initDriver()
    {
        $this->driver::init($this->driverSettings);
    }

    protected function newDriverInstanceAndCall($method, $arg): AdapterAbstract
    {
        // можно передавать нужные аргументы в конструктор
        return (new $this->driver(/* ... */))->$method(...$arg);
    }
}
