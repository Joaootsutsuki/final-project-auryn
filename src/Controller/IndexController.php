<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/{vueRoute}', name: 'app_vue', requirements: ['vueRoute' => '^(?!api).*$'], defaults: ['vueRoute' => null])]
    public function index(): Response
    {
        return $this->render('base.html.twig'); 
    }
    
}
