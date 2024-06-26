<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/', name: 'page_')]
class PageController extends AbstractController
{
    #[Route('/', name: 'home', methods: [Request::METHOD_GET])]
    public function home(): Response
    {
        return $this->render('page/home.html.twig');
    }

    #[Route('/login', name: 'login', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $username = $request->request->get('username');

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('page/login.html.twig', ['username' => $username, 'error' => $error]);
    }

    #[Route('/loading', name: 'loading', methods: [Request::METHOD_GET])]
    public function loading(Request $request): Response
    {
        return $this->render('page/loading.html.twig');
    }

    #[Route('/feed', name: 'feed', methods: [Request::METHOD_GET])]
    public function dashboard(): Response
    {
        return $this->render('page/feed.html.twig');
    }

    #[Route('/settings', name: 'settings', methods: [Request::METHOD_GET])]
    public function settings(): Response
    {
        return $this->render('page/settings.html.twig');
    }
}
