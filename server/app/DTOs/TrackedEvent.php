<?php

declare(strict_types=1);

namespace App\DTOs;

final class TrackedEvent
{
    public function __construct(
        public readonly string $status,
        public \DateTimeImmutable $eventedAt,
        public readonly ?string $location = null,
        public readonly ?string $destination = null,
    ) {
    }
}
