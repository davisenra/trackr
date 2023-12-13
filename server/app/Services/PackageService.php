<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\TrackerInterface;
use App\DTOs\CreateNewPackage;
use App\DTOs\TrackedEvent;
use App\Enums\PackageStatus;
use App\Exceptions\PackageException;
use App\Jobs\TrackPackageEvents;
use App\Models\Package;
use App\Models\PackageEvent;
use App\Models\User;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

final class PackageService
{
    /**
     * @return Collection<int, Package>
     */
    public function allUndeliveredSinceLastTrack(\DateTimeInterface $cutoff = null): Collection
    {
        if (is_null($cutoff)) {
            $cutoff = new \DateTimeImmutable('10 minutes ago');
        }

        return Package::query()
            ->where('status', '!=', PackageStatus::DELIVERED)
            ->where('last_tracked_at', '<=', $cutoff->format('Y-m-d H:i:s'))
            ->get();
    }

    /**
     * @return Collection<Package>
     */
    public function allByUser(User $user, bool $loadPackageEvents = true): Collection
    {
        $packages = Package::where('user_id', $user->id);

        if ($loadPackageEvents) {
            $packages->with('events');
        }

        return $packages
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function findById(int $packageId): ?Package
    {
        return Package::where('id', $packageId)
            ->with([
                'events' => function (HasMany $qb) {
                    $qb->orderBy('evented_at', 'DESC');
                },
            ])
            ->first();
    }

    public function delete(Package $package): void
    {
        DB::transaction(function (Connection $connection) use ($package) {
            try {
                $package->events()->delete();
                $package->delete();
            } catch (\Throwable) {
                $connection->rollBack();
            }
        });
    }

    public function trackNewPackage(CreateNewPackage $payload): Package
    {
        $trackingCode = $payload->trackingCode;

        $duplicatedPackage = Package::where([
            'tracking_code' => $trackingCode,
            'user_id' => $payload->user->id,
        ]);

        $packageAlreadyBeingTracked = $duplicatedPackage->first() instanceof Package;

        if ($packageAlreadyBeingTracked) {
            throw PackageException::alreadyBeingTracked($trackingCode);
        }

        $package = new Package([
            'name' => $payload->name,
            'tracking_code' => $trackingCode,
            'description' => $payload->description ?: null,
            'status' => PackageStatus::DRAFT,
        ]);

        $package->user()->associate($payload->user);
        $package->save();

        TrackPackageEvents::dispatch($package->id);

        return $package;
    }

    public function trackEvents(TrackerInterface $trackingService, Package $package): void
    {
        $events = $trackingService->track($package->tracking_code);

        $this->persistPackageEvents($package, $events);
    }

    /**
     * @param TrackedEvent[] $trackedEvents
     */
    private function persistPackageEvents(Package $package, array $trackedEvents): void
    {
        /** @var string[] $previouslyTrackedEventHashes */
        $previouslyTrackedEventHashes = $package->events
            ->map(fn (PackageEvent $event) => $event->status_hash)
            ->toArray();

        foreach ($trackedEvents as $trackedEvent) {
            $statusHash = $this->definePackageEventStatusHash($trackedEvent);
            $eventAlreadyTracked = in_array($statusHash, $previouslyTrackedEventHashes);

            if ($eventAlreadyTracked) {
                continue;
            }

            $event = new PackageEvent([
                'status' => $trackedEvent->status,
                'status_hash' => $statusHash,
                'location' => $trackedEvent->location,
                'destination' => $trackedEvent->destination,
                'evented_at' => $trackedEvent->eventedAt,
            ]);

            $event->package()->associate($package);
            $event->save();
        }

        $package->fresh();
        $package->definePackageStatus();
        $package->bumpLastTrackedAt();
        $package->update();
    }

    private function definePackageEventStatusHash(TrackedEvent $event): string
    {
        $string = sprintf(
            '%s%s',
            $event->status,
            $event->eventedAt->format('Y-m-d H:i:s')
        );

        return md5($string);
    }
}
