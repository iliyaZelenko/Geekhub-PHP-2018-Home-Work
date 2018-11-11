<?php

require '../../../vendor/autoload.php';
//header('Content-Type: image/png');
use IlyaZelen\SuperImagesStatic as SuperImages;



SuperImages::init('GD'); // ImageMagick


$color = \IlyaZelen\Colors\UniversalColor::getColorInstanceByValue('rgba(0, 128, 0, 0)');
var_dump($color);
var_dump($color->convertToHex());
var_dump($color->convertToRGB());
var_dump($color->convertToName());
