<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\Query\GetFeedUpdatesQuery;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/', name: 'page_')]
class PageController extends AbstractController
{
    public function __construct(private readonly CommandBus $queryBus)
    {
    }

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

    #[Route('/dashboard', name: 'dashboard', methods: [Request::METHOD_GET])]
    public function dashboard(Request $request): Response
    {
        return $this->render('page/dashboard.html.twig');
    }

    #[Route('/feed', name: 'feed', methods: [Request::METHOD_GET])]
    public function feed(): Response
    {
        $feedEntries = $this->queryBus->handle(new GetFeedUpdatesQuery());


        return $this->render('fragment/message.html.twig', ['entries' => $feedEntries]);
    }
}
