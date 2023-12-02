<?php

declare(strict_types=1);

namespace App\Services\Correios;

use App\Contracts\TrackerInterface;
use App\DTOs\TrackedEvent;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Collection;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

final class LinkTrackApiTracker implements TrackerInterface
{
    private const BASE_URL = 'https://api.linketrack.com';
    private const TRACKING_ENDPOINT = '/track/json';

    public function __construct(
        private readonly string $username,
        #[\SensitiveParameter]
        private readonly string $token,
        private readonly ClientInterface $httpClient,
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    public function track(string $trackingCode): array
    {
        $request = new Request('GET', $this->buildRequestUri($trackingCode), [
            'Accept' => 'application/json',
        ]);

        $response = $this->httpClient->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            $this->logger?->error(
                'Error while tracking package',
                [
                    'status_code' => $response->getStatusCode(),
                    'tracking_code' => $trackingCode,
                    'response' => $response->getBody()->getContents(),
                ],
            );

            throw new LinkTrackApiException(sprintf('LinkTrack API returned an error: %s', $response->getStatusCode()));
        }

        $responseContent = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $apiEvents = collect($responseContent->eventos);

        if ($apiEvents->isEmpty()) {
            $this->logger?->error(
                'No events found',
                [
                    'tracking_code' => $trackingCode,
                    'response' => $response->getBody()->getContents(),
                ],
            );

            throw new LinkTrackApiException(sprintf('No events found for package: %s', $trackingCode));
        }

        return $this->mapResponseToTrackedEvents($apiEvents);
    }

    private function buildRequestUri(string $trackingCode): string
    {
        return self::BASE_URL.self::TRACKING_ENDPOINT.'?'.http_build_query([
            'user' => $this->username,
            'token' => $this->token,
            'codigo' => $trackingCode,
        ]);
    }

    /**
     * @php-stan-ignore-next-line
     *
     * @param Collection<object> $apiEvents
     *
     * @return TrackedEvent[]
     */
    private function mapResponseToTrackedEvents(Collection $apiEvents): array
    {
        $trackedEvents = $apiEvents->map(function (object $event) {
            $eventedAt = \DateTimeImmutable::createFromFormat(
                'd/m/Y H:i:s',
                sprintf('%s %s', $event->data, $event->hora),
            );

            $statusHash = md5($event->status.$eventedAt->format('Y-m-d H:i:s'));
            $location = $event->local;

            $subStatuses = collect($event->subStatus)
                ->filter(fn (string $subStatus) => str_contains($subStatus, 'Destino'));

            if ($subStatuses->isNotEmpty()) {
                $formattedDestination = str_replace('Destino: ', '', $subStatuses->first());
            }

            return new TrackedEvent(
                $event->status,
                $eventedAt,
                $location,
                $formattedDestination ?? null,
            );
        });

        return $trackedEvents->toArray();
    }
}
