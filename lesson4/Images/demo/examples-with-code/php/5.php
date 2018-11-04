<?php

require '../../../vendor/autoload.php';
header('Content-Type: image/png');
use App\SuperImages;



SuperImages::init('ImageMagick'); // GD


$color = \App\Clasess\Color\UniversalColor::getColorInstanceByValue('rgba(0, 128, 0, 0)');
var_dump($color);
var_dump($color->convertToHex());
var_dump($color->convertToRGB());
var_dump($color->convertToName());
