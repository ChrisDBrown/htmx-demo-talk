<?php

declare(strict_types=1);

namespace App\Domain\Model\Entity;

use App\Domain\Model\Enum\ReactType;
use App\Domain\Repository\ReactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

#[ORM\Entity(repositoryClass: ReactRepository::class)]
class React
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        private UuidV7 $id,
        #[ORM\Column(type: 'string', enumType: ReactType::class)]
        private ReactType $type,
        #[ORM\Column(type: 'integer')]
        private int $offset,
        #[ORM\Column(length: 180)]
        private string $username,
        #[ORM\Column(type: UuidType::NAME, nullable: true)]
        private ?UuidV7 $userId,
    ) {
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getType(): ReactType
    {
        return $this->type;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserId(): ?UuidV7
    {
        return $this->userId;
    }
}
