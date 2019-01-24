<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AlgoliaExtension extends AbstractExtension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    // TODO мне просто хотелось получить значение из конфига, не знаю правильно ли это, я следовал этому гайду:
    // https://symfony.com/doc/current/service_container/parameters.html#getting-and-setting-container-parameters-in-php
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions(): array
    {
        return [
            // возвращает полное имя индекса Algolia (используется префикс)
            new TwigFunction('appAlgoliaGetIndexName', function ($indexKey) {
                $indexes = $this->container->getParameter('app.algolia.indexes');
                $indexName = $indexes[$indexKey];
                $indexPrefix = getenv('ALGOLIA_INDEX_PREFIX');

                return $indexPrefix . '_' . $indexName;
            })
        ];
    }
}
