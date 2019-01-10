<?php

namespace App\Utils\Slugger;

// TODO мне нужно было в Entity использоват метод slugify, хотел сделать через DI чтобы получать класс по интерфейсу,
// чтобы не нарушать принцип DIP (SOLID), хотел в конструкторе получал мой класс Slugger, но не получилось,
// наверное вообще полный бред делаю, не знаю как лучше сделать.
interface SluggerInterface
{
    public function slugify($str);
}
