<?php

declare(strict_types=1);

namespace App\Application\Command;

use Symfony\Component\Uid\UuidV7;

class AddUserMessageCommand
{
    public function __construct(
        public readonly string $content,
        public readonly UuidV7 $userId,
    ) {
    }
}
