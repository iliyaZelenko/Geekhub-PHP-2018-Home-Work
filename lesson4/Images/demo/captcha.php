<?php

require '../vendor/autoload.php';

//header('Content-Type: image/png');


use App\SuperImages;

SuperImages::init('GD', [ // ImageMagick
    'driverSettings' => [
        'fonts' => [
            'mySuperFontAlias' => [
                'path' => './fonts/font.ttf'
            ],
            'myFont' => [
                'path' => './fonts/BlackCasperFont.ttf'
            ]
        ]
    ]
]);




//echo SuperImages::new(500, 500) // rgba(0, 128, 0, 127)
////    ->crop(20, 40, 300, 200)
//    ->fit(null, 200)
//    ->border('yellow', 2)
//    ->rotate(25, 'rgba(128, 0, 0, 0.5)')
//    ->border('blue', 2)
//    ->save('./img/aaa.png')
//    ->output('png', 100);
//    ->crop(120, 20, 300, 200)
//    ->crop(0, 0, 400, 120)
//    ->fit(400, 200)
//    ->rotate(45, 'rgba(128, 0, 0, 0.1)', true)
//    ->resize(800, 600)
//    ->flip('both')
//    ->fit(800, 600) // 800, 600


function captcha (array $options = []) {
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
    $metrics = SuperImages::queryFontMetrics($text, $fontSize);
    [$textWidth, $textHeight] = $metrics->getSizeCompact();
    $originalFontMetrics = $metrics->getOriginal();

    // печально что вот это в GD нельзя получить отступ между символами, приходится таким способом угадывать
    $lettersMargin = $originalFontMetrics['maxHorizontalAdvance'] ?? round($fontSize * .85); // 30
    $xStart = $xPaddings;
    $yStart = $yPaddings + $fontSize;
    $imageWidth = $textWidth + $xPaddings * 2;
    $imageHeight = $textHeight + $yPaddings * 2 + ($lettersTurning < 5 ?: 15);


    $image = SuperImages::new($imageWidth, $imageHeight, $background);

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


