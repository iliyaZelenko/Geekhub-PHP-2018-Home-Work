<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(int $page = 1)
    {
        return $this->render('blog/main_page.html.twig');
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
