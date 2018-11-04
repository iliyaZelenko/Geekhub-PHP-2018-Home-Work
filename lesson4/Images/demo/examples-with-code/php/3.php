<?php

require '../../../vendor/autoload.php';
header('Content-Type: image/png');
use App\SuperImages;



SuperImages::init('GD'); // ImageMagick

echo SuperImages::open(__DIR__ . './img/2.jpg')
    ->crop(400, 200, 800, 620)
    ->fit(600, 400) // 800, 600
    ->output('png');
