<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Http\Requests\StorePackageRequest;
use App\Models\User;

final class CreateNewPackage
{
    public function __construct(
        public readonly User $user,
        public readonly string $name,
        public readonly string $trackingCode,
        public readonly ?string $description = null
    ) {
    }

    public static function fromRequest(User $user, StorePackageRequest $request): CreateNewPackage
    {
        return new CreateNewPackage(
            $user,
            $request['name'],
            $request['trackingCode'],
            $request['description'] ?? null,
        );
    }
}
