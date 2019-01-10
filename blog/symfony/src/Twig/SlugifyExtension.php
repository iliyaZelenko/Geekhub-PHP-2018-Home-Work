<?php

namespace App\Twig;

use App\Helpers;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SlugifyExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            // TODO попробовать использовать непосредственно slufigy из helpers
            new TwigFunction('slugify', [Helpers::class, 'slugify']),
        ];
    }
}
