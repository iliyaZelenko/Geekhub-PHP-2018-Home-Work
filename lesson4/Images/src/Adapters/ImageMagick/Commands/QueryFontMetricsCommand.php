<?php

namespace IlyaZelen\Adapters\ImageMagick\Commands;

use IlyaZelen\Adapters\Commands\AbstractCommand;
use IlyaZelen\Adapters\AbstractAdapter;
use IlyaZelen\Adapters\Commands\ArgumentsTraits\QueryFontMetricArgumentsTrait;
use IlyaZelen\FontMetric;

/*
 * Возвращает метрики строки по шрифту
 */
class QueryFontMetricsCommand extends AbstractCommand
{
    use QueryFontMetricArgumentsTrait;

    /**
     * @param \Imagick $image
     * @param AbstractAdapter $driver
     * @return FontMetric
     * @throws \ImagickException
     */
    public function execute(&$image, AbstractAdapter $driver): FontMetric
    {
        $this->setArguments();

        $text = $this->argument('text');
        $size = $this->argument('size');
        $font = $this->argument('font');
        // В Imagick это не требуется
        // $angle = $this->argument('angle');


        $font = $driver->getFont($font);

        $emptyImage = new \Imagick();
        $draw = new \ImagickDraw();
        $draw->setFont($font);
        $draw->setFontSize($size);

        $data = $emptyImage->queryFontMetrics($draw, $text);

        return new FontMetric([
            'textWidth' => $data['textWidth'],
            'textHeight' => $data['textHeight'],
            'original' => $data
        ]);
    }
}
