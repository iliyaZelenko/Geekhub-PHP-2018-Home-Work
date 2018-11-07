<?php

namespace IlyaZelen\Command;


use IlyaZelen\FactoryMethod\NewVariant\Drivers\AbstractDriver;

require '../../../vendor/autoload.php';

?><pre><?php

$driver = AbstractDriver::init('GD');

$driver->newImage(100, 50);
