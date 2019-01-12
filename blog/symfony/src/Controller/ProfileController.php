<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    public function index()
    {
        return $this->render('user/profile/index.html.twig');
    }
}
