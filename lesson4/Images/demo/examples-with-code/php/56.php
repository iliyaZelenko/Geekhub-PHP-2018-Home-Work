<?php

header('Content-Type: image/png');
require '../../../vendor/autoload.php';
use IlyaZelen\SuperImagesStatic as SuperImages;

SuperImages::init('ImageMagick'); // ImageMagick


//echo SuperImages::new(500, 500) // rgba(0, 128, 0, 127)
////    ->crop(20, 40, 300, 200)
//    ->fit(null, 200)
//    ->border('yellow', 2)
//    ->rotate(25, 'rgba(128, 0, 0, 0.5)')
//    ->border('blue', 2)
//    ->save('./img/aaa.png')
//    ->output('png', 100);

// для GD работает и без __DIR__
echo SuperImages::open(__DIR__ . './img/3.png')
//    ->crop(120, 20, 300, 200)
    ->crop(100, 200, 800, 620)
//    ->fit(400, 200)
//    ->rotate(45, 'rgba(128, 0, 0, 0.1)', true)
//    ->resize(800, 600)
    ->flip('both')
    ->fit(600, 400) // 800, 600
    ->output('png');


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
//;


//$color = \IlyaZelen\Colors\UniversalColor::getColorInstanceByValue('rgba(0, 128, 0, 0)');
//var_dump($color);
//var_dump($color->convertToHex());
//var_dump($color->convertToRGB());
//var_dump($color->convertToName());
