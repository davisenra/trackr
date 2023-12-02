<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use App\Jobs\TrackPackageEvents;
use App\Models\Package;
use App\Models\PackageEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PackageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAllEndpointsRequireAuthentication(): void
    {
        $this->getJson('/api/v1/packages')
            ->assertUnauthorized();

        $this->getJson('/api/v1/packages/1')
            ->assertUnauthorized();

        $this->postJson('/api/v1/packages')
            ->assertUnauthorized();

        $this->deleteJson('/api/v1/packages/1')
            ->assertUnauthorized();
    }

    public function testItReturnsAllUserPackages(): void
    {
        $user = User::factory()->create();

        $packages = Package::factory(5)->create([
            'user_id' => $user,
        ]);

        $this->actingAs($user);

        $this->getJson('/api/v1/packages')
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('data.0.id', $packages[0]->id)
            ->assertJsonPath('data.0.events', null);
    }

    public function testItCanFindById(): void
    {
        $user = User::factory()->create();

        $package = Package::factory()->createOne([
            'user_id' => $user,
        ]);

        PackageEvent::factory(5)->create([
            'package_id' => $package->id,
        ]);

        $this->actingAs($user);

        $this->getJson("/api/v1/packages/$package->id")
            ->assertOk()
            ->assertJsonIsObject('data')
            ->assertJsonPath('data.id', $package->id)
            ->assertJsonIsArray('data.events')
            ->assertJsonIsObject('data.events.0')
            ->assertJsonIsObject('data.events.1')
            ->assertJsonIsObject('data.events.2')
            ->assertJsonIsObject('data.events.3')
            ->assertJsonIsObject('data.events.4');
    }

    public function testItCannotSeePackagesFromDifferentUsers(): void
    {
        $userA = User::factory()->create();

        $userB = User::factory()->create();
        $packageB = Package::factory()->createOne([
            'user_id' => $userB,
        ]);

        $this->actingAs($userA);

        $this->getJson("/api/v1/packages/$packageB->id")
            ->assertNotFound();
    }

    public function testItCannotDeletePackagesFromDifferentUsers(): void
    {
        $userA = User::factory()->create();

        $userB = User::factory()->create();
        $packageB = Package::factory()->createOne([
            'user_id' => $userB,
        ]);

        $this->assertDatabaseHas(Package::class, [
            'id' => $packageB->id,
        ]);

        $this->actingAs($userA);

        $this->deleteJson("/api/v1/packages/$packageB->id")
            ->assertNoContent();

        $this->assertDatabaseHas(Package::class, [
            'id' => $packageB->id,
        ]);
    }

    public function testItCanTrackANewPackage(): void
    {
        Queue::fake([TrackPackageEvents::class]);

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson('/api/v1/packages', [
            'name' => 'Package A',
            'trackingCode' => 'NE123456789BR',
            'description' => 'A brief description',
        ]);

        Queue::assertPushed(TrackPackageEvents::class);

        $this->assertDatabaseHas(Package::class, [
            'user_id' => $user->id,
            'name' => 'Package A',
            'tracking_code' => 'NE123456789BR',
            'description' => 'A brief description',
        ]);

        $response->assertCreated()
            ->assertJsonIsObject('data')
            ->assertJsonPath('data.name', 'Package A')
            ->assertJsonPath('data.trackingCode', 'NE123456789BR')
            ->assertJsonPath('data.description', 'A brief description')
            ->assertJsonPath('data.status', 'draft');
    }

    public function testCannotTrackNewPackageWithoutRequiredFields(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->postJson('/api/v1/packages', [
            'trackingCode' => 'NE123456789BR',
            'description' => 'A brief description',
        ])->assertUnprocessable();

        $this->postJson('/api/v1/packages', [
            'name' => 'New package',
            'description' => 'A brief description',
        ])->assertUnprocessable();

        $this->postJson('/api/v1/packages', [
            'description' => 'A brief description',
        ])->assertUnprocessable();
    }
}
