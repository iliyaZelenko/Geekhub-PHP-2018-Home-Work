<?php

namespace IlyaZelen;

class FontMetric
{
    /**
     * @var float
     */
    protected $width;

    /**
     * @var float
     */
    protected $height;

    /**
     * @var array
     */
    protected $original;

    public function __construct(array $data)
    {
        $this->width = (float) $data['textWidth'];
        $this->height = (float) $data['textHeight'];
        $this->original = (array) $data['original'];
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getOriginal(): array
    {
        return $this->original;
    }

    public function getSizeCompact(): array
    {
        return [
            $this->getWidth(),
            $this->getHeight()
        ];
    }
}
