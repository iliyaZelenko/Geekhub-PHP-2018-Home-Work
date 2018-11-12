<?php

namespace IlyaZelen\Command;


require '../../vendor/autoload.php';

?><pre><?php

$image = new Image();

$lastCommandResult = $image
    ->resize(100, 200)
    ->save('images/myImage.jpg');

echo "Результат последней команды: $lastCommandResult\n";
