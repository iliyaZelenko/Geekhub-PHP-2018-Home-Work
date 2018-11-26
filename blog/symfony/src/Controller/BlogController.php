<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\{Response, JsonResponse, Cookie};

class BlogController extends AbstractController
{
    private $router;
    private $routerGenerator;

    public function __construct(UrlGeneratorInterface $routerGenerator, RouterInterface $router)
    {
        $this->routerGenerator = $routerGenerator;
        $this->router = $router;
    }

    public function posts(int $page = 1)
    {
        $perPage = 4;
        $postCount = 100;
        $posts = [];

        for ($i = 1; $i < $postCount; $i++) {
            $posts[] = [
                'title' => 'Пост под номером ' . $i,
                'text' => 'Описание поста под номером ' . $i
            ];
        }
        $chunked = array_chunk($posts, $perPage);
        $indexByPage = $page - 1;


        if (!isset($chunked[$indexByPage])) {
            throw $this->createNotFoundException("Страницы $page не существует. Последняя страница: " . \count($chunked));
        }

        return new JsonResponse([
            'posts' => $chunked[$indexByPage],
            'pages' => \count($chunked),
            'page' => $page
        ]);
    }

    public function routes()
    {
        $routes = [];

        foreach ($this->router->getRouteCollection()->all() as $route_name => $route) {
            $routes[$route_name] = $route->getPath();
        }

        // 200 по умолчанию
        $response = new JsonResponse($routes, Response::HTTP_OK);
        // не работает
        $response->headers->setCookie(new Cookie('foo', 'bar'));


        return $response;
    }

    public function index(int $page = 1)
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'page' => $page,
            'route' => $this->routerGenerator->generate('posts', [
                'page' => 1337,
                'category' => '123',
            ]),
            'absoluteRoute' => $this->generateUrl(
                'posts',
                [
                    'page' => 1337
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        ]);
    }
}
