<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\TrackerInterface;
use App\Services\PackageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;

class TrackPackageEvents implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly int $packageId
    ) {
    }

    public function handle(
        TrackerInterface $tracker,
        PackageService $packageService,
        LoggerInterface $logger,
    ): void {
        $package = $packageService->findById($this->packageId);

        if (is_null($package)) {
            $logger->info('Package not found while tracking events', [
                'package_id' => $this->packageId,
            ]);

            return;
        }

        try {
            $packageService->trackEvents($tracker, $package);
        } catch (\Throwable $e) {
            $logger->error('Error while tracking package events', [
                'package_id' => $this->packageId,
                'exception' => $e->getMessage(),
            ]);

            return;
        }
    }
}
