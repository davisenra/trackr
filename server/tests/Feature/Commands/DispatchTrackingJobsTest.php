<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\Enums\PackageStatus;
use App\Jobs\TrackPackageEvents;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DispatchTrackingJobsTest extends TestCase
{
    use RefreshDatabase;

    public function testItDispatchesAJobForEachUndeliveredPackage(): void
    {
        $userA = User::factory()->createOne();
        $userB = User::factory()->createOne();

        $status = [
            PackageStatus::DRAFT,
            PackageStatus::POSTED,
            PackageStatus::IN_TRANSIT,
            PackageStatus::OUT_FOR_DELIVERY,
        ];

        Package::factory()->createOne([
            'user_id' => $userA->id,
            'status' => PackageStatus::DELIVERED,
            'last_tracked_at' => new \DateTimeImmutable('19 minutes ago'),
        ]);

        Package::factory(2)->create([
            'user_id' => $userA->id,
            'status' => fake()->randomElement($status),
            'last_tracked_at' => new \DateTimeImmutable('19 minutes ago'),
        ]);

        Package::factory(2)->create([
            'user_id' => $userB->id,
            'status' => fake()->randomElement($status),
            'last_tracked_at' => new \DateTimeImmutable('13 minutes ago'),
        ]);

        Queue::fake([TrackPackageEvents::class]);

        $this->artisan('app:dispatch-tracking-jobs')->assertSuccessful();

        Queue::assertPushed(TrackPackageEvents::class, 4);
    }
}
