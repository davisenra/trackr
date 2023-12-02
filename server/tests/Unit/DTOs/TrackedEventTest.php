<?php

namespace Tests\Unit\DTOs;

use App\DTOs\TrackedEvent;
use PHPUnit\Framework\TestCase;

class TrackedEventTest extends TestCase
{
    public function testItCanBeCreated(): void
    {
        $trackedEvent = new TrackedEvent(
            status: 'status',
            eventedAt: new \DateTimeImmutable(),
            location: 'location',
            destination: 'destination',
        );

        $this->assertInstanceOf(TrackedEvent::class, $trackedEvent);
    }

    public function testItCanBeCreatedWithoutOptionalValues(): void
    {
        $trackedEvent = new TrackedEvent(
            status: 'status',
            eventedAt: new \DateTimeImmutable(),
        );

        $this->assertInstanceOf(TrackedEvent::class, $trackedEvent);
    }
}
