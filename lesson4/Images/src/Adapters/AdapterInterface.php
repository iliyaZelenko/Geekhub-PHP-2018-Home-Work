<?php

namespace IlyaZelen\Adapters;

//use IlyaZelen\FontMetric;
//use IlyaZelen\Size;

// не уверен что стоит выносить в интерфейс когда можно написать в абстрактном классе
interface AdapterInterface
{
    public function __construct($settings = []);

    // Создает пустое изображение и заливает его одним цветом.
//    public function new(int $width, int $height, string $backgroundColor = null): AbstractAdapter;

    // Считывает изображение по указанному пути.
//    public function open(string $path): AbstractAdapter;

    // Возвращает размер изображения в пикселях.
//    public function getSize(): Size;

    // Делает обрезку.
//    public function crop(int $x, int $y, int $width, int $height): AbstractAdapter;

    // Возвращает изображение в строковом виде.
//    public function output(string $format, int $compression = 65): string;

    // Сохраняет изображение по указанному пути.
//    public function save(string $path): AbstractAdapter;

    /**
     * Меняет размер. Если не указанна одна из сторон, то не указанная строна подсчитается по соотношению
     * старой указанной стороны к новой, в результате не указанная сторона будет пропорциональна указанной (их соотношение не изменится).
     */
//    public function resize(int $newWidth = null, int $newHeight = null): AbstractAdapter;

    // Рамка в которую должно поместиться изображение. Можно указать и только одну величину, не указанная будет проигнорированна.
//    public function fit(int $boundaryWidth = null, int $boundaryHeight = null): AbstractAdapter;

    /*
     * Делает поворот изображения. При повороте по умолчанию изображение не обрезается, обрезка включается через $crop парметр.
     * Вообще хотел сделать такую обрезку: https://i.imgur.com/gpQchqH.png --> https://i.imgur.com/vwqn7QF.png, но там много нюансов.
     * Вот ее алгоритм (что хотел сделать): https://stackoverflow.com/questions/5789239/calculate-largest-rectangle-in-a-rotated-rectangle/22511805#22511805
     */
//    public function rotate($angle, $backgroundColor = null, $crop = false): AbstractAdapter;

    // рисует рамку
//    public function border($color = 'black', $thickness = 1): AbstractAdapter;

    // делает переворот картики
//    public function flip($mode = 'vertical'): AbstractAdapter;

    // рисует текст
//    public function text(string $text, $x = 0, $y = 0, string $color = 'black', string $font = null, int $size = 20, int $angle = 0): AbstractAdapter;

    // возвращает метрики строки из шрифта
//    public function queryFontMetrics(string $text, int $size, string $font = null, int $angle = 0): FontMetric;
}

