<?php

namespace App\Twig\MyTemplateEngine;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
//use Symfony\Component\Templating\PhpEngine;

class MyEngine extends TwigEngine
{
    private $container;

    public function __construct(Environment $environment, TemplateNameParserInterface $parser, \Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        parent::__construct($environment, $parser);

        // autowiring выручает
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, array $parameters = [])
    {
        $config = $this->getConfig($name);
        // сливает параметры переданные в render (этот метод вызывается из AbstractController (ControllerTrait) из его метода render)
        $merged = array_merge($parameters, $config);

        dump($merged);
        return $this->load($name)->render($merged);
    }

    // загружает конфиг, берет значения указаной вьюхи если они там есть
    private function getConfig($name)
    {
        // хотелось бы как-то попроще образатся получать kernel и путь к директории проекта
        $configsPath = $this->container->get('kernel')->getProjectDir() . '/config/';

        $config = Yaml::parse(
            file_get_contents($configsPath . 'view-composer.yaml')
        );
        $nameWithoutExt = strtok($name, '.');

        return $config[$nameWithoutExt] ?? [];
    }
}
