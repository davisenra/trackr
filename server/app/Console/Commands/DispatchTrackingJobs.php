<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\TrackPackageEvents;
use App\Models\Package;
use App\Services\PackageService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    'app:dispatch-tracking-jobs',
    'Dispatch TrackPackageEvents jobs for all active packages'
)]
class DispatchTrackingJobs extends Command
{
    public function __construct(
        private readonly PackageService $packageService
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $packagesToBeTracked = $this->packageService->allUndeliveredSinceLastTrack();
        $packagesToBeTracked->each(fn (Package $package) => TrackPackageEvents::dispatch($package->id));
    }
}
