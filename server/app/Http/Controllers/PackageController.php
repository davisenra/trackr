<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\CreateNewPackage;
use App\Http\Requests\StorePackageRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Models\User;
use App\Services\PackageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class PackageController extends Controller
{
    public function __construct(
        private readonly PackageService $packageService
    ) {
    }

    public function index(Request $request): JsonResource
    {
        /** @var User $user */
        $user = $request->user();
        $packages = $this->packageService->allByUser($user, false);

        return PackageResource::collection($packages);
    }

    public function show(Request $request, int $packageId): JsonResource
    {
        $package = $this->packageService->findById($packageId);

        if (is_null($package) || $request->user()->cannot('view', $package)) {
            abort(404, 'Package not found');
        }

        return new PackageResource($package);
    }

    public function store(StorePackageRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $payload = CreateNewPackage::fromRequest($user, $request);

        $package = $this->packageService->trackNewPackage($payload);

        return (new PackageResource($package))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(Request $request, Package $package): Response
    {
        if ($request->user()->cannot('delete', $package)) {
            abort(204);
        }

        $this->packageService->delete($package);

        return response()->noContent();
    }
}
