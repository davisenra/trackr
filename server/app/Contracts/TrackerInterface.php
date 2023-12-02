<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTOs\TrackedEvent;

interface TrackerInterface
{
    /**
     * @return TrackedEvent[]
     */
    public function track(string $trackingCode): array;
}
