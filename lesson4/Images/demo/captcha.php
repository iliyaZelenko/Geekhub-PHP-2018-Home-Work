<?php

require '../vendor/autoload.php';

use IlyaZelen\SuperImages;


function captcha (array $options = []) {
    $superImages = new SuperImages('GD', [ // ImageMagick
        'driverSettings' => [
            'fonts' => [
                'mySuperFontAlias' => [
                    'path' => __DIR__ . './fonts/font.ttf'
                ],
                'myFont' => [
                    'path' => __DIR__ . './fonts/BlackCasperFont.ttf'
                ]
            ]
        ]
    ]);

    // сколько будет символов
    $letters = $options['letters'] ?? 8;
    $background = $options['background'] ?? 'white';
    // амплитуда поворота
    $lettersTurning = $options['lettersTurning'] ?? 25;
    $fontSize = $options['fontSize'] ?? 35;
    // отступы от текста
    $xPaddings = $options['xPaddings'] ?? 15;
    $yPaddings = $options['yPaddings'] ?? 10;
    // доступные цвета для символов
    $colors = $options['colors'] ?? array_merge([
        // библиотека поодеживает все форматы цветов
        'blue',
        'yellow',
        'black',
        'red',
        'green',
        '#FF33B5',
        'rgb(255, 144, 0)',
        'rgba(128, 0, 0, 0.7)'
        // жаль что нельзя как в JS: ...$options['moreColors']
    ], $options['moreColors'] ?? []);


    // trim не подходит, символов должно быть в точности сколько в $letters
    $text = substr(getRandomStr(), 0, $letters);
    $lastLetterIndex = strlen($text) - 1;

    // заменяет проблел в конце на точку
    if ($text[$lastLetterIndex] === ' ') $text[$lastLetterIndex] = '.';

    // метрики шрифта
    $metrics = $superImages->queryFontMetrics($text, $fontSize);
    [$textWidth, $textHeight] = $metrics->getSizeCompact();
    $originalFontMetrics = $metrics->getOriginal();

    // печально что вот это в GD нельзя получить отступ между символами, приходится таким способом угадывать
    $lettersMargin = $originalFontMetrics['maxHorizontalAdvance'] ?? $options['lettersMargin'] ?? round($fontSize * .85);
    $xStart = $xPaddings;
    $yStart = $yPaddings + $fontSize;
    $imageWidth = $textWidth + $xPaddings * 2;
    $imageHeight = $textHeight + $yPaddings * 2 + ($lettersTurning < 5 ? 0 : 15);


    $image = $superImages->new($imageWidth, $imageHeight, $background);

    for ($i = 0; $i < $letters; $i++) {
        // случайные значения
        $color = $colors[array_rand($colors, 1)];
        $angle = random_int(-$lettersTurning, $lettersTurning);

        $image->text(
            $text[$i],
            $xStart,
            $yStart,
            $color,
            'mySuperFontAlias',
            $fontSize,
            $angle
        );
        $xStart += $lettersMargin;
    }

    return [
        'text' => $text,
        'image' => $image->output('png')
    ];
}

function getRandomStr () {
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' =>
                "X-Mashape-Key: PadRDm8eZrmshZN0GtWmon1JL7edp1dWKVgjsnx5GH2OIbynDe\r\n" .
                "Content-Type: application/x-www-form-urlencoded\r\n" .
                "Accept: application/json\r\n"

        ]
    ];

    $context = stream_context_create($opts);

    // открывает файл с помощью установленных выше HTTP-заголовков
    $file = file_get_contents('https://andruxnet-random-famous-quotes.p.mashape.com/?cat=famous&count=1', false, $context);

    return json_decode($file, true)[0]['quote'];
}


