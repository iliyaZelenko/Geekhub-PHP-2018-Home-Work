<?php

namespace IlyaZelen\Adapters\GD\Commands;

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
     * @param $image
     * @param AbstractAdapter $driver
     * @return FontMetric
     */
    public function execute(&$image, AbstractAdapter $driver): FontMetric
    {
        $this->setArguments();

        $text = $this->argument('text');
        $size = $this->argument('size');
        $font = $this->argument('font');
        $angle = $this->argument('angle');


        $font = $driver->getFont($font);

        $data = imagettfbbox($size, $angle, $font, $text);

        return new FontMetric([
            'textWidth' => $data[2] - $data[0],
            'textHeight' => $data[1] - $data[5],
            'original' => $data
        ]);
    }
}
