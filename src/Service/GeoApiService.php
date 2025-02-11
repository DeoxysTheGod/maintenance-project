<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeoApiService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Fetches region data from the Geo API.
     *
     * @return array The region data
     *
     * @throws TransportExceptionInterface If the HTTP request fails
     */
    public function getRegionData(): array
    {
        $response = $this->client->request('GET', 'https://geo.api.gouv.fr/regions', [
            'timeout' => 10, // Set timeout in seconds
        ]);

        // Check if response status code is 200 OK
        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException('Failed to fetch regions data. Status code: '.$response->getStatusCode());
        }

        return $response->toArray(); // This will throw an exception if the response body is invalid
    }

    /**
     * Fetches departments data for a specific region from the Geo API.
     *
     * @param int $regionCode The region code
     *
     * @return array The departments data
     *
     * @throws TransportExceptionInterface If the HTTP request fails
     */
    public function getRegionDepartmentsData(string $regionCode): array
    {
        $response = $this->client->request('GET', 'https://geo.api.gouv.fr/regions/'.$regionCode.'/departements', [
            'timeout' => 10, // Set timeout in seconds
        ]);

        // Check if response status code is 200 OK
        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException('Failed to fetch departments data for region '.$regionCode.'. Status code: '.$response->getStatusCode());
        }

        return $response->toArray(); // This will throw an exception if the response body is invalid
    }
}
