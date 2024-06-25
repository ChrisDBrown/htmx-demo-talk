<?php

declare(strict_types=1);

namespace App\Application\Authenticator;

use App\Domain\Model\Entity\User;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Uid\Uuid;

class CreateAndLoginAuthenticator extends AbstractLoginFormAuthenticator
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[\Override]
    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('page_login');
    }

    #[\Override]
    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username');
        if (null === $request->request->get('username')) {
            throw new CustomUserMessageAuthenticationException('Username is required');
        }

        if (!\is_string($username) || !ctype_alnum($username)) {
            throw new CustomUserMessageAuthenticationException('Username must be alphanumeric');
        }

        $username = trim($username);
        if (\strlen($username) < 5) {
            throw new CustomUserMessageAuthenticationException('Username must be at least 5 characters');
        }

        if ($this->userRepository->findUserByUsername($username) instanceof User) {
            throw new CustomUserMessageAuthenticationException(sprintf('Username %s already exists', $username));
        }

        $user = new User(Uuid::v7(), $username);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }

    #[\Override]
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('page_dashboard'));
    }
}
