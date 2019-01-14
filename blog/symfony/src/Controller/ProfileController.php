<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    public function index()
    {
        // TODO не уверен нужно ли это писать, если писал - { path: ^/profile, roles: IS_AUTHENTICATED_FULLY }
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('user/profile/index.html.twig');
    }
}