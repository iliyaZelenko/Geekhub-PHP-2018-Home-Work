<?php

namespace IlyaZelen;

use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\GD\GDAdapter;
use IlyaZelen\Adapters\ImageMagick\ImageMagickAdapter;
use IlyaZelen\FontMetric;

class SuperImages
{
    public static $drivers = [
        'GD' => GDAdapter::class,
        'ImageMagick' => ImageMagickAdapter::class
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
        $this->driver = AbstractAdapter::factory($driver, $settings['driverSettings'] ?? []);
    }

    // Варианты взаимодействия с адаптерами через которые создаются объекты адаптеров и дальше уже с ними  можно работать.
    public function new(...$arg): AbstractAdapter
    {
        return $this->driver->{__FUNCTION__}(...$arg);
    }
    public function open(...$arg): AbstractAdapter
    {
        return $this->driver->{__FUNCTION__}(...$arg);
    }

    public function queryFontMetrics(...$arg): FontMetric
    {
        return $this->driver->queryFontMetrics(...$arg);
    }
}
