<?php

namespace App\Twig\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function getFilters()
    {
        // If your filter generates SAFE HTML, you should add a third parameter: ['is_safe' => ['html']]
        // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
        return [
            new TwigFilter('filterName', [AppRuntime::class, 'name']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('functionPrice', [AppRuntime::class, 'price']),
        ];
    }

    public function getGlobals()
    {
        return [
            'globalFromExt' => 'getGlobals'
        ];
    }
}


