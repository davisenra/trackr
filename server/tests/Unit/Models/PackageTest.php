<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Enums\PackageStatus;
use App\Models\Package;
use App\Models\PackageEvent;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanBumpLastTrackedAtProperty(): void
    {
        $package = new Package();

        $this->assertNull($package->last_tracked_at);

        $package->bumpLastTrackedAt();

        $this->assertNotNull($package->last_tracked_at);
    }

    /**
     * @dataProvider packageStatusDataProvider
     */
    public function testItSetsTheCorrectStatusAfterEvents(string $eventStatus, PackageStatus $expectedStatus, string $eventedAt): void
    {
        $package = Package::factory()->createOne([
            'user_id' => User::factory()->createOne(),
            'status' => PackageStatus::DRAFT,
        ]);

        PackageEvent::factory()->createOne([
            'status' => $eventStatus,
            'package_id' => $package->id,
            'evented_at' => new CarbonImmutable($eventedAt),
        ]);

        $package->load('events');
        $package->definePackageStatus();
        $package->update();

        $this->assertSame($package->status, $expectedStatus);
    }

    public static function packageStatusDataProvider(): array
    {
        return [
            ['Objeto postado', PackageStatus::POSTED, '-1 month'],
            ['Mercadoria em trânsito, por favor aguarde', PackageStatus::IN_TRANSIT, '-20 days'],
            ['Objeto saiu para entrega ao destinatário', PackageStatus::OUT_FOR_DELIVERY, '-15 days'],
            ['Objeto entregue ao destinatário', PackageStatus::DELIVERED, '-10 days'],
        ];
    }
}
