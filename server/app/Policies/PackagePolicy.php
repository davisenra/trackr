<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Package;
use App\Models\User;

class PackagePolicy
{
    public function view(User $user, Package $package): bool
    {
        return $package->user_id === $user->id;
    }

    public function delete(User $user, Package $package): bool
    {
        return $package->user_id === $user->id;
    }
}
