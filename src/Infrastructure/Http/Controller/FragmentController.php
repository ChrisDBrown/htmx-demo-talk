<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fragment', name: 'fragment_')]
class FragmentController extends AbstractController
{
    #[Route('/login', name: 'login', methods: [Request::METHOD_GET])]
    public function login(): Response
    {
        return $this->render('fragment/login.html.twig', ['error' => null]);
    }

    #[Route('/tabs', name: 'tabs', methods: [Request::METHOD_GET])]
    public function tabs(): Response
    {
        return $this->render('fragment/tabs.html.twig');
    }

    #[Route('/settings', name: 'settings', methods: [Request::METHOD_GET])]
    public function settings(): Response
    {
        return $this->render('fragment/settings.html.twig');
    }

    #[Route('/moods', name: 'moods', methods: [Request::METHOD_GET])]
    public function moods(): Response
    {
        return $this->render('fragment/moods.html.twig');
    }
}
