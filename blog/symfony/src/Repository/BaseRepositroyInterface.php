<?php

namespace App\Repository;

// Такой интерфейс было бы удобно использовать с generic types, подставлять тип Entity при имплементации (в PHP такого нет)
use Doctrine\Common\Persistence\ObjectRepository;

interface BaseRepositroyInterface extends ObjectRepository
{

}
