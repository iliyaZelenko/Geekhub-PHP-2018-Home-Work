<?php

require '../../../vendor/autoload.php';
//header('Content-Type: image/png');
use App\SuperImages;



SuperImages::init('ImageMagick', [ // ImageMagick
    'driverSettings' => [
        'fonts' => [
            'mySuperFontAlias' => [
                'path' => '../../fonts/font.ttf'
            ]
        ]
    ]
]);

echo SuperImages::open(__DIR__ . './img/3.png')
    ->resize(600)
    ->border('blue', 25)
    ->rotate(45)
    ->text(
        'SuperImages',
        0,
        20,
        'rgba(255, 0, 0, 0.5)',
        'mySuperFontAlias',
        90,
        15
    )
    ->fit(600, 400)
    ->output('png');
