<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(TranslatorInterface $translator, int $page = 1): Response
    {
        return $this->render('blog/main_page.html.twig', [
            // TODO не зависит от _locale роута
            'hello_translated' => $translator->trans('hello_translated'),
            'count' => 1
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
