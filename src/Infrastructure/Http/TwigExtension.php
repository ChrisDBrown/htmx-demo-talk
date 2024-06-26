<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Domain\Model\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function __construct(private readonly Security $security)
    {
    }

    #[\Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('feedtime', function (int $offset): \DateTimeImmutable {
                $startTime = new \DateTimeImmutable();
                $user = $this->security->getUser();
                if ($user instanceof User) {
                    $startTime = $user->getCreatedAt();
                }

                return $startTime->add(new \DateInterval(sprintf('PT%dS', $offset)));
            }),
        ];
    }
}
