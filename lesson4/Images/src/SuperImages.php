<?php

namespace App;

use App\Adapters\{AdapterAbstract, GD, ImageMagick};

class SuperImages
{
    protected const ERROR_INVALID_DRIVER = 'Переданный драйвер не поддерживается.';

    protected static $drivers = [
        'GD' => GD::class,
        'ImageMagick' => ImageMagick::class
    ];

    // какой драйвер картинок используется из доступных, ставится при инициализации
    protected static $driver;
    // настройки выбранного драйвера
    protected static $driverSettings;

    // поддерживаемые форматы (GD ограничивает)
    public static $supportedFormats = ['png', 'jpg', 'jpeg', 'gif'];


    // ставит используемый драйвер
    public static function init(string $driver = 'GD', $settings = []): void
    {
        if (!isset(static::$drivers[$driver])) {
            throw new \InvalidArgumentException(static::ERROR_INVALID_DRIVER);
        }

        // настройки выбранного драйвера
        static::$driverSettings = $settings['driverSettings'] ?? [];

        static::$driver = static::$drivers[$driver];
        static::initDriver();
    }

    // Варианты взаимодействия с адаптерами через которые создаются объекты адаптеров и дальше уже с ними  можно работать.
    public static function new(...$arg): AdapterAbstract
    {
        return static::newDriverInstanceAndCall('new', $arg);
    }
    public static function open(...$arg): AdapterAbstract
    {
        return static::newDriverInstanceAndCall('open', $arg);
    }
    // TODO возвращаемый тип: FontMetrics
    public static function queryFontMetrics(...$arg)
    {
        return static::$driver::queryFontMetrics(...$arg);
    }

    // инициализация драйвера которая проходит один раз на этапе инициализации библиотеки
    protected static function initDriver()
    {
        static::$driver::init(static::$driverSettings);
    }

    protected static function newDriverInstanceAndCall($method, $arg): AdapterAbstract
    {
        // можно передавать нужные аргументы в конструктор
        return (new static::$driver(/* ... */))->$method(...$arg);
    }
}
