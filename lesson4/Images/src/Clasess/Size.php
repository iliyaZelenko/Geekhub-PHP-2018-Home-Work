<?php

namespace App\Clasess;

/**
 * Класс который помогает стандартизировать формат возвращаемого размера, чтобы каждый провадер должен был указывать и ширину и длину.
 * Конечно это не так критично, просто пример как можно сделать когда ситуация посложнее возникнет.
 */
class Size
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getCompact(): array
    {
        return [
            $this->getWidth(),
            $this->getHeight()
        ];
    }
}
