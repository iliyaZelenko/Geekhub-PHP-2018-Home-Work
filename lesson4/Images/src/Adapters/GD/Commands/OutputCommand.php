<?php

namespace IlyaZelen\Adapters\GD\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\OutputArgumentsTrait;
use function IlyaZelen\{getSuffixByFormat};

// Возвращает изображение в строковом виде.
class OutputCommand extends AbstractCommand
{
    use OutputArgumentsTrait;

    /**
     * @param $image
     * @param AbstractAdapter $driver
     * @return string
     */
    public function execute(&$image, AbstractAdapter $driver): string
    {
        $this->setArguments();

        $format = $this->argument('format');
        $compression = $this->argument('compression');


        $driver->checkFormatSupport($format);

        // чтобы считывать вывод
        ob_start();
        { // скобки для красоты
            if (!$suffix = getSuffixByFormat($format)) {
                throw new \InvalidArgumentException(AbstractAdapter::ERROR_FORMAT_SUPPORT);
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
            $function($image, null, ...$params);

            $buffer = ob_get_contents();
        }
        ob_end_clean();

        imagedestroy($image);

        return $buffer;
    }
}
