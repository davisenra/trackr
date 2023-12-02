<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Contracts\TrackerInterface;
use App\DTOs\TrackedEvent;
use App\Enums\PackageStatus;
use App\Jobs\TrackPackageEvents;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery\MockInterface;
use Tests\TestCase;

class TrackPackageEventsTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanTrackPackageEvents(): void
    {
        $user = User::factory()->createOne();
        $package = Package::factory()->createOne([
            'user_id' => $user->id,
            'status' => PackageStatus::DRAFT,
        ]);

        $this->mock(TrackerInterface::class, function (MockInterface $mock) {
            /* @phpstan-ignore-next-line */
            $mock->expects('track')
                ->once()
                ->andReturn([
                    new TrackedEvent(
                        'Objeto postado',
                        new \DateTimeImmutable('-1 week'),
                    ),
                    new TrackedEvent(
                        'Objeto saiu para entrega',
                        new \DateTimeImmutable('-3 days'),
                    ),
                ]);
        });

        TrackPackageEvents::dispatchSync($package->id);

        $package = $package->fresh();
        $events = $package->events;

        $this->assertCount(2, $events);
        $this->assertSame($package->status, PackageStatus::OUT_FOR_DELIVERY);
    }

    public function testItFailsIfNoEventsAreFound(): void
    {
        $user = User::factory()->createOne();
        $package = Package::factory()->createOne([
            'user_id' => $user->id,
        ]);

        $this->mock(TrackerInterface::class, function (MockInterface $mock) {
            /* @phpstan-ignore-next-line */
            $mock->expects('track')
                ->once()
                ->andReturn([]);
        });

        Log::shouldReceive('error', 'Error while tracking package events');

        TrackPackageEvents::dispatchSync($package->id);

        $events = $package->events;

        $this->assertCount(0, $events);
    }
}
