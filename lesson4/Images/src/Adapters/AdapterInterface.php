<?php

namespace App\Adapters;

use App\Clasess\FontMetric;
use App\Clasess\Size;

// не уверен что стоит выносить в интерфейс когда можно написать в абстрактном классе
interface AdapterInterface
{
    public static function init($settings = []);

    // Создает пустое изображение и заливает его одним цветом.
    public function new(int $width, int $height, string $backgroundColor = null): AdapterAbstract;

    // Считывает изображение по указанному пути.
    public function open(string $path): AdapterAbstract;

    // Возвращает размер изображения в пикселях.
    public function getSize(): Size;

    // Делает обрезку.
    public function crop(int $x, int $y, int $width, int $height): AdapterAbstract;

    // Возвращает изображение в строковом виде.
    public function output(string $format, int $compression = 65): string;

    // Сохраняет изображение по указанному пути.
    public function save(string $path): AdapterAbstract;

    /**
     * Меняет размер. Если не указанна одна из сторон, то не указанная строна подсчитается по соотношению
     * старой указанной стороны к новой, в результате не указанная сторона будет пропорциональна указанной (их соотношение не изменится).
     */
    public function resize(int $newWidth = null, int $newHeight = null): AdapterAbstract;

    // Рамка в которую должно поместиться изображение. Можно указать и только одну величину, не указанная будет проигнорированна.
    public function fit(int $boundaryWidth = null, int $boundaryHeight = null): AdapterAbstract;

    /*
     * Делает поворот изображения. При повороте по умолчанию изображение не обрезается, обрезка включается через $crop парметр.
     * Вообще хотел сделать такую обрезку: https://i.imgur.com/gpQchqH.png --> https://i.imgur.com/vwqn7QF.png, но там много нюансов.
     */
    public function rotate($angle, $backgroundColor = null, $crop = false): AdapterAbstract;

    // рисует рамку
    public function border($color = 'black', $thickness = 1): AdapterAbstract;

    // делает переворот картики
    public function flip($mode = 'vertical'): AdapterAbstract;

    // рисует текст
    public function text(string $text, $x = 0, $y = 0, string $color = 'black', string $font = null, int $size = 20, int $angle = 0);

    // возвращает метрики строки из шрифта
    public static function queryFontMetrics(string $text, int $size, string $font = null, int $angle = 0): FontMetric;
}
