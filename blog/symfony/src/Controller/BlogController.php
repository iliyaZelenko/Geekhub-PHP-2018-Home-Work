<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    public function index(int $page = 1)
    {
        return $this->render('blog/mainPage.html.twig', [
            'controller_name' => 'BlogController',
            'page' => $page,
        ]);
    }

    /*
    public function routes()
    {
        $routes = [];

        foreach ($this->router->getRouteCollection()->all() as $route_name => $route) {
            $routes[$route_name] = $route->getPath();
        }

        return new JsonResponse($routes, Response::HTTP_OK);
    }*/
}
