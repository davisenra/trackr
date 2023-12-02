<?php

namespace App\Models;

use App\Enums\PackageStatus;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => PackageStatus::class,
        'last_tracked_at' => 'immutable_datetime',
    ];

    /**
     * @return BelongsTo<User, Package>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<PackageEvent>
     */
    public function events(): HasMany
    {
        return $this->hasMany(PackageEvent::class);
    }

    public function definePackageStatus(): void
    {
        $this->load('events');

        $latestEvent = $this->events
            ->sortByDesc('evented_at')
            ->first();

        if (is_null($latestEvent)) {
            $this->status = PackageStatus::DRAFT;

            return;
        }

        $statusMapping = [
            'postado' => PackageStatus::POSTED,
            'em trânsito' => PackageStatus::IN_TRANSIT,
            'encaminhado' => PackageStatus::IN_TRANSIT,
            'saiu para entrega' => PackageStatus::OUT_FOR_DELIVERY,
            'entregue' => PackageStatus::DELIVERED,
        ];

        foreach ($statusMapping as $keyword => $status) {
            if (str_contains($latestEvent->status, $keyword)) {
                $this->status = $status;

                return;
            }
        }

        $this->status = PackageStatus::DRAFT;
    }

    public function bumpLastTrackedAt(): void
    {
        $this->last_tracked_at = new CarbonImmutable('now');
    }
}
