<?php

namespace App\Adapters;

use App\SuperImages;

abstract class AdapterAbstract implements AdapterInterface
{
    protected const ERROR_FORMAT_SUPPORT = 'Этот формат не подерживается.';
    protected const UNABLE_TO_FIND_FILE = 'Не удалось найти файл.';
    protected const UNABLE_TO_DECODE_FILE = 'Не удалосб декодировать файл.';
    // эти константы получаются из хелпера getFont
    public const ERROR_NO_FONTS = 'У вас не указанны шрифты.';
    public const ERROR_NO_FONT_EXISTS = 'У вас не указан этот шрифт.';

    // текущее изображение
    protected $image;
    // установленные шрифты
    protected static $fonts = [];

    abstract protected function getColor(string $value);

    protected function checkFormatSupport($format): void
    {
        if (!\in_array($format, SuperImages::$supportedFormats, true) ) {
            throw new \InvalidArgumentException(static::ERROR_FORMAT_SUPPORT);
        }
    }
}
