<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Model\Enum\ReactType;
use Symfony\Component\Uid\UuidV7;

class AddUserReactCommand
{
    public function __construct(
        public readonly ReactType $type,
        public readonly UuidV7 $userId,
    ) {
    }
}
