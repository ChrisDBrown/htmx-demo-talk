<?php

declare(strict_types=1);

namespace App\Application\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener implements EventSubscriberInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[\Override]
    public static function getSubscribedEvents(): array
    {
        return [LogoutEvent::class => 'onLogout'];
    }

    public function onLogout(LogoutEvent $event): void
    {
        $token = $event->getToken();
        if (!$token instanceof \Symfony\Component\Security\Core\Authentication\Token\TokenInterface) {
            return;
        }

        $user = $token->getUser();
        if (!$user instanceof \Symfony\Component\Security\Core\User\UserInterface) {
            return;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
