<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\Command\AddUserMessageCommand;
use App\Application\Command\AddUserReactCommand;
use App\Application\Query\GetFeedUpdatesQuery;
use App\Application\Query\GetFullFeedQuery;
use App\Domain\Model\Entity\User;
use App\Domain\Model\Enum\ReactType;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/feed', name: 'feed_')]
class FeedController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $queryBus,
        private readonly CommandBus $commandBus,
    ) {
    }

    #[Route('/full', name: 'full', methods: [Request::METHOD_POST])]
    public function feed(): Response
    {
        $feedEntries = $this->queryBus->handle(new GetFullFeedQuery());

        return $this->render('fragment/feed.html.twig', ['entries' => $feedEntries]);
    }

    #[Route('/new', name: 'new', methods: [Request::METHOD_GET])]
    public function new(): Response
    {
        $feedEntries = $this->queryBus->handle(new GetFeedUpdatesQuery());

        return $this->render('fragment/message.html.twig', ['entries' => $feedEntries]);
    }

    #[Route('/react', name: 'react', methods: [Request::METHOD_POST])]
    public function react(Request $request): Response
    {
        $react = $request->query->get('react');

        if (null === $react) {
            return $this->render('fragment/closed-moods.html.twig');
        }

        $reactType = ReactType::tryFrom($react);

        if (!$reactType instanceof ReactType) {
            return $this->render('fragment/closed-moods.html.twig');
        }

        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->render('fragment/closed-moods.html.twig');
        }

        $this->commandBus->handle(new AddUserReactCommand($reactType, $user->getId()));

        return $this->render('fragment/closed-moods.html.twig');
    }

    #[Route('/message', name: 'message', methods: [Request::METHOD_POST])]
    public function message(Request $request): Response
    {
        $content = $request->request->get('comment');

        if (null === $content || !\is_string($content) || '' === trim($content)) {
            return $this->render('fragment/comment.html.twig');
        }

        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->render('fragment/comment.html.twig');
        }

        $this->commandBus->handle(new AddUserMessageCommand(trim($content), $user->getId()));

        return $this->render('fragment/comment.html.twig');
    }
}
