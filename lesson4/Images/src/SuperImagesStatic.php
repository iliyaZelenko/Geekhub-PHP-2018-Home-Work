<?php

namespace IlyaZelen;

use IlyaZelen\Adapters\{AbstractAdapter};
use IlyaZelen\FontMetric;

class SuperImagesStatic
{
    // какой драйвер картинок используется из доступных, ставится при инициализации
    protected static $driver;


    // ставит используемый драйвер
    public static function init(string $driver = 'GD', $settings = []): void
    {
        static::$driver = new SuperImages($driver, $settings);
    }

    // Варианты взаимодействия с адаптерами через которые создаются объекты адаптеров и дальше уже с ними  можно работать.
    public static function new(...$arg): AbstractAdapter
    {
        return static::$driver->{__FUNCTION__}(...$arg);
    }
    public static function open(...$arg): AbstractAdapter
    {
        return static::$driver->{__FUNCTION__}(...$arg);
    }

    public static function queryFontMetrics(...$arg): FontMetric
    {
        return static::$driver->queryFontMetrics(...$arg);
    }
}
