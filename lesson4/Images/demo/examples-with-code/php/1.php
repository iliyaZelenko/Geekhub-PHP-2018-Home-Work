<?php

header('Content-Type: image/png');
require '../../../vendor/autoload.php';
use IlyaZelen\SuperImagesStatic as SuperImages;



SuperImages::init('GD'); // ImageMagick

echo SuperImages::new(400, 100, 'blue')
    ->output('png');
