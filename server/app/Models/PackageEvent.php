<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageEvent extends Model
{
    use HasFactory;

    protected $casts = [
        'evented_at' => 'immutable_datetime',
    ];

    /**
     * @return BelongsTo<Package, PackageEvent>
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
