<?php

namespace App;

// изначально хотел тут объявить просто функцию, но autoloader выдавал ошибку https://i.imgur.com/MzUSDnn.png
//function slufigy ($str) {
//    // ...
//}

class Helpers {
    public static function slugify($str)
    {
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', '-', $str);

        return $str;
    }
}
