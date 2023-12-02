<?php

namespace App\Providers;

use App\Contracts\TrackerInterface;
use App\Services\Correios\LinkTrackApiTracker;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

class TrackingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TrackerInterface::class, LinkTrackApiTracker::class);
    }

    public function boot(): void
    {
        $this->app->bind(TrackerInterface::class, function ($app) {
            return new LinkTrackApiTracker(
                username: env('LINK_TRACK_API_USERNAME'),
                token: env('LINK_TRACK_API_TOKEN'),
                httpClient: $app->make(ClientInterface::class),
                logger: $app->make(LoggerInterface::class),
            );
        });
    }
}
