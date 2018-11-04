<?php

header('Content-Type: image/png');
require '../../../vendor/autoload.php';
use App\SuperImages;



SuperImages::init('GD'); // ImageMagick

echo SuperImages::new(400, 100, 'blue')
    ->output('png');
