<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home', methods: [Request::METHOD_GET])]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/switch', name: 'switch', methods: [Request::METHOD_GET])]
    public function switch(): Response
    {
        return $this->render('switch.html.twig');
    }
}
