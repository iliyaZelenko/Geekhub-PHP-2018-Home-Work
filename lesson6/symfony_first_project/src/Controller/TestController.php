<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    public function index()
    {
        return $this->render('test/index.html.twig', [
            'fromController' => 'TestController',
            'ifVariable' => true,
            'numbers' => [
                1, 2, 3
            ],
            'users' => [
                [
                    'name' => 'Vasya'
                ],
                [
                    'name' => 'Vasya2'
                ]
            ],
            'htmlVar' => '<i>Content</i>',
            'treeVar' => [
                'val1',
                ['val2', 'val3', [
                    'val4', 'val5'
                ]],
                'val6'
            ]
        ]);
    }
}
