<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Contracts\TrackerInterface;
use App\DTOs\CreateNewPackage;
use App\DTOs\TrackedEvent;
use App\Enums\PackageStatus;
use App\Exceptions\PackageException;
use App\Jobs\TrackPackageEvents;
use App\Models\Package;
use App\Models\PackageEvent;
use App\Models\User;
use App\Services\PackageService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\TestCase;

class PackageServiceTest extends TestCase
{
    use RefreshDatabase;

    private PackageService $packageService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(TrackerInterface::class, function (MockInterface $mock) {
            /* @phpstan-ignore-next-line */
            $mock->expects('track')
                ->atMost()
                ->times(1)
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

        $trackingService = $this->app->get(TrackerInterface::class);

        $this->packageService = new PackageService($trackingService);
    }

    public function testItCanBeInstantiated(): void
    {
        $this->assertInstanceOf(PackageService::class, $this->packageService);
    }

    public function testItReturnsACollectionWithAllUserPackages(): void
    {
        $user = User::factory()->create();

        Package::factory(5)->create([
            'user_id' => $user,
        ]);

        $packages = $this->packageService->allByUser($user);

        $this->assertInstanceOf(Collection::class, $packages);
        $this->assertNotEmpty($packages);
        $this->assertCount(5, $packages);
    }

    public function testItEagerLoadsPackageEvents(): void
    {
        $user = User::factory()->create();

        $packages = Package::factory(2)->create([
            'user_id' => $user,
        ]);

        $packages->each(function (Package $package) {
            PackageEvent::factory(5)->create([
                'package_id' => $package,
            ]);
        });

        $packages = $this->packageService->allByUser($user);

        $this->assertInstanceOf(Collection::class, $packages);
        $this->assertNotEmpty($packages);
        $this->assertCount(2, $packages);
        $this->assertTrue($packages->first()->relationLoaded('events'));
    }

    public function testEagerLoadingPackageEventsIsOptional(): void
    {
        $user = User::factory()->create();

        $package = Package::factory(1)->create([
            'user_id' => $user,
        ]);

        PackageEvent::factory(5)->create([
            'package_id' => $package->first(),
        ]);

        $packages = $this->packageService->allByUser($user, false);

        $this->assertInstanceOf(Collection::class, $packages);
        $this->assertNotEmpty($packages);
        $this->assertCount(1, $packages);
        $this->assertFalse($packages->first()->relationLoaded('events'));
    }

    public function testItCanDeleteAPackage(): void
    {
        $user = User::factory()->create();

        $package = Package::factory()->createOne([
            'user_id' => $user,
        ]);

        $this->assertDatabaseHas(Package::class, [
            'id' => $package->id,
        ]);

        $this->packageService->delete($package);

        $this->assertNull($this->packageService->findById($package->id));
    }

    public function testItCanTrackAPackage(): void
    {
        Queue::fake([TrackPackageEvents::class]);

        $user = User::factory()->create();

        $payload = new CreateNewPackage(
            $user,
            'Package',
            'NA123456789BR',
            'A brief description',
        );

        $package = $this->packageService->trackNewPackage($payload);

        Queue::assertPushed(TrackPackageEvents::class);

        $this->assertDatabaseHas(Package::class, [
            'id' => $package->id,
        ]);

        $this->assertSame($user, $package->user);
        $this->assertEquals('Package', $package->name);
        $this->assertEquals('NA123456789BR', $package->tracking_code);
        $this->assertEquals('A brief description', $package->description);
        $this->assertEquals(PackageStatus::DRAFT, $package->status);
    }

    public function testUserCannotTrackTheSamePackageTwice(): void
    {
        Queue::fake([TrackPackageEvents::class]);

        $user = User::factory()->create();

        $payloadA = new CreateNewPackage(
            $user,
            'Package A',
            'NA123456789BR',
        );

        $package = $this->packageService->trackNewPackage($payloadA);

        Queue::assertPushed(TrackPackageEvents::class);

        $this->assertDatabaseHas(Package::class, [
            'id' => $package->id,
        ]);

        $this->assertSame($user, $package->user);
        $this->assertEquals('Package A', $package->name);
        $this->assertEquals('NA123456789BR', $package->tracking_code);
        $this->assertEquals(PackageStatus::DRAFT, $package->status);

        $payloadB = new CreateNewPackage(
            $user,
            'Package B',
            'NA123456789BR',
        );

        $this->expectException(PackageException::class);
        $this->expectExceptionMessage(
            sprintf('The package with tracking code: %s is already being tracked by the user', $payloadB->trackingCode)
        );

        $this->packageService->trackNewPackage($payloadB);
    }

    public function testDefaultStatusForNewPackageIsDraft(): void
    {
        Queue::fake([TrackPackageEvents::class]);

        $user = User::factory()->create();

        $payloadA = new CreateNewPackage(
            $user,
            'Package A',
            'NA123456789BR',
        );

        $package = $this->packageService->trackNewPackage($payloadA);

        Queue::assertPushed(TrackPackageEvents::class);

        $this->assertDatabaseHas(Package::class, [
            'id' => $package->id,
        ]);

        $this->assertSame($user, $package->user);
        $this->assertEquals('Package A', $package->name);
        $this->assertEquals('NA123456789BR', $package->tracking_code);
        $this->assertEquals(PackageStatus::DRAFT, $package->status);
    }

    public function testItCanTrackPackageEvents(): void
    {
        $user = User::factory()->createOne();
        $package = Package::factory()->createOne([
            'user_id' => $user->id,
        ]);

        $this->packageService->trackEvents($package);

        $events = PackageEvent::where(['package_id' => $package->id])->get();

        $this->assertCount(2, $events);

        $this->assertNotNull($events[0]->status);
        $this->assertNotNull($events[0]->status_hash);
        $this->assertInstanceOf(\DateTimeImmutable::class, $events[0]->evented_at);

        $this->assertNotNull($events[1]->status);
        $this->assertNotNull($events[1]->status_hash);
        $this->assertInstanceOf(\DateTimeImmutable::class, $events[1]->evented_at);
    }

    public function testItReturnsAllUndeliveredPackages(): void
    {
        $userA = User::factory()->createOne();
        $userB = User::factory()->createOne();

        Package::factory(2)->create([
            'user_id' => $userA->id,
            'status' => PackageStatus::DELIVERED,
        ]);

        Package::factory(3)->create([
            'user_id' => $userB->id,
            'status' => PackageStatus::DELIVERED,
        ]);

        $status = [
            PackageStatus::DRAFT,
            PackageStatus::POSTED,
            PackageStatus::IN_TRANSIT,
            PackageStatus::OUT_FOR_DELIVERY,
        ];

        Package::factory(5)->create([
            'user_id' => $userA->id,
            'status' => fake()->randomElement($status),
        ]);

        Package::factory(5)->create([
            'user_id' => $userB->id,
            'status' => fake()->randomElement($status),
        ]);

        $cutoff = new \DateTime('now');
        $undeliveredPackages = $this->packageService->allUndeliveredSinceLastTrack($cutoff);

        $this->assertCount(10, $undeliveredPackages);
        $this->assertInstanceOf(Collection::class, $undeliveredPackages);
        $this->assertEmpty($undeliveredPackages->filter(
            fn (Package $package) => PackageStatus::DELIVERED === $package->status)
        );
    }

    public function testDefaultCutoffTimeForUndeliveredPackagesIsTenMinutesAgo(): void
    {
        $user = User::factory()->createOne();

        Package::factory()->createOne([
            'user_id' => $user->id,
            'status' => PackageStatus::IN_TRANSIT,
            'last_tracked_at' => new \DateTimeImmutable('1 day ago'),
        ]);

        Package::factory()->createOne([
            'user_id' => $user->id,
            'status' => PackageStatus::OUT_FOR_DELIVERY,
            'last_tracked_at' => new \DateTimeImmutable('3 minutes ago'),
        ]);

        $undeliveredPackages = $this->packageService->allUndeliveredSinceLastTrack();
        $this->assertCount(1, $undeliveredPackages);
        $this->assertInstanceOf(Collection::class, $undeliveredPackages);
    }
}
