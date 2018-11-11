<?php

namespace IlyaZelen\Adapters\GD\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\OpenArgumentsTrait;
use function IlyaZelen\{getSuffixByFormat};

// Считывает изображение по указанному пути.
class OpenCommand extends AbstractCommand
{
    use OpenArgumentsTrait;

    public function execute(&$image, AbstractAdapter $driver)
    {
        $this->setArguments();

        $path = $this->argument('path');

        if (!file_exists($path)) {
            throw new \InvalidArgumentException(AbstractAdapter::UNABLE_TO_FIND_FILE);
        }

        // TODO возможно достаточно определять разширение в строке $path
        // $extension = substr(strrchr($path, '.'), 1);

        // mime тип файла в указанном пути
        $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);

        if (!$suffix = getSuffixByFormat($mime)) {
            throw new \InvalidArgumentException(AbstractAdapter::ERROR_FORMAT_SUPPORT);
        }

        // вот так можно красиво вызывать ф-ю)
        $function = 'imagecreatefrom' . $suffix;
        // хоть можно и так ('imagecreatefrom' . $suffix)($path);
        $image = $function($path);

        // если вернуло false, то произошла ошибка
        if (!$image) {
            throw new \InvalidArgumentException(AbstractAdapter::UNABLE_TO_DECODE_FILE);
        }

        $this->image = $image;
    }
}
