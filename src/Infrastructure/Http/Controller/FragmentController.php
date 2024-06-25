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
    #[Route('/switch', name: 'switch', methods: [Request::METHOD_GET])]
    public function switch(): Response
    {
        return $this->render('fragment/switch.html.twig');
    }

    #[Route('/login', name: 'login', methods: [Request::METHOD_GET])]
    public function login(): Response
    {
        return $this->render('fragment/login.html.twig', ['error' => null]);
    }

    #[Route('/feed', name: 'feed', methods: [Request::METHOD_GET])]
    public function feed(): Response
    {
        return $this->render('fragment/feed.html.twig');
    }

    #[Route('/settings', name: 'settings', methods: [Request::METHOD_GET])]
    public function settings(): Response
    {
        return $this->render('fragment/settings.html.twig');
    }

    #[Route('/message', name: 'message', methods: [Request::METHOD_GET])]
    public function message(): Response
    {
        return $this->render('fragment/message.html.twig');
    }

    #[Route('/moods', name: 'moods', methods: [Request::METHOD_GET])]
    public function moods(): Response
    {
        return $this->render('fragment/moods.html.twig');
    }

    #[Route('/closed-moods', name: 'closed_moods', methods: [Request::METHOD_GET])]
    public function closedMoods(): Response
    {
        return $this->render('fragment/closed-moods.html.twig');
    }
}
