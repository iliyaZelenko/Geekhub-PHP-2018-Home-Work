<?php

namespace App\Twig\Extensions;

use Twig\Extension\RuntimeExtensionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AppRuntime implements RuntimeExtensionInterface
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function name($name)
    {
        return strtoupper($name);
    }

    public function price(int $num)
    {
        return $num + 100;
    }

    public function resolveRoute($name, $params)
    {
        return $this->router->generate($name, $params);
    }
}
