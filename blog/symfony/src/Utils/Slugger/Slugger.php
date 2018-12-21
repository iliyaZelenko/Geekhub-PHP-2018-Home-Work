<?php

namespace App\Utils\Slugger;

class Slugger // implements SluggerInterface
{
    public static function slugify($str)
    {
        return preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($str)), 'UTF-8'));
    }
}
