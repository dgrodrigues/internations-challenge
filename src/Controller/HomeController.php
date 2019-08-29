<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $number = random_int(0, 100);

        return $this->render('Home/index.html.twig', [
            'number' => $number,
        ]);
    }
}