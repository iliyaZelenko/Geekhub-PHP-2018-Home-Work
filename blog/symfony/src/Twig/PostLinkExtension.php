<?php

namespace App\Twig;

use App\Entity\Post;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PostLinkExtension extends AbstractExtension
{
    private $routerGenerator;

    // TODO Lazy-loaded
    public function __construct(UrlGeneratorInterface $routerGenerator)
    {
        $this->routerGenerator = $routerGenerator;
    }

    public function getFunctions()
    {
        return [
            // генерирует ссылку на пост
            new TwigFunction('postLink', function (Post $post) {
                return $this->routerGenerator->generate('post', [
                    'slug' => $post->getSlug(),
                    'id' => $post->getId()
                ]);
            }),
        ];
    }
}
