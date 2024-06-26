<?php

declare(strict_types=1);

namespace App\Domain\Model\Enum;

enum ReactType: string
{
    case FIRE = 'fire';
    case LOVE = 'love';
    case SMILE = 'smile';
    case THUMBS = 'thumbs';

    public function emoji(): string
    {
        return match ($this) {
            ReactType::FIRE => '🔥',
            ReactType::LOVE => '❤️',
            ReactType::SMILE => '🙂',
            ReactType::THUMBS => '👍',
        };
    }
}
