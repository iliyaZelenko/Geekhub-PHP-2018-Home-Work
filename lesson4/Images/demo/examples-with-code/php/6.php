<?php

require '../../../vendor/autoload.php';
//header('Content-Type: image/png');
use IlyaZelen\SuperImagesStatic as SuperImages;



SuperImages::init('ImageMagick', [ // ImageMagick
    'driverSettings' => [
        'fonts' => [
            'mySuperFontAlias' => [
                'path' => '../../fonts/font.ttf'
            ]
        ]
    ]
]);

SuperImages::open(__DIR__ . './img/3.png')
    ->resize(450, 460)
    ->fit(250, 250)
    ->rotate(50, 'yellow', true)
    ->border('red', 5)
    ->flip()
    ->text(
        'SuperImages',
        20,
        160,
        'rgba(255, 0, 0, 0.5)',
        'mySuperFontAlias',
        70,
        15
    )
    ->save(__DIR__ . './img/111.png');
