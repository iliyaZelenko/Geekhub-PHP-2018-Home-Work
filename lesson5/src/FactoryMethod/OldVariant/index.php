<?php

namespace IlyaZelen\Command;



use IlyaZelen\FactoryMethod\OldVariant\ConcreteCreatorGD;

require '../../../vendor/autoload.php';

?><pre><?php

$driver = (new ConcreteCreatorGD())->init();

$driver->newImage(100, 50);
