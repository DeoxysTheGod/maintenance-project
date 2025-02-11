<?php

namespace App\Tests\Service;

use App\Service\GeoApiService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeoApiServiceTest extends TestCase
{
    private GeoApiService $geoApiService;
    private HttpClientInterface $httpClient;

    protected function setUp(): void
    {
        // Créez une instance de MockHttpClient
        $this->httpClient = new MockHttpClient();

        // Créez une instance du service en injectant le client mock
        $this->geoApiService = new GeoApiService($this->httpClient);
    }

    public function testGetRegionData(): void
    {
        // Simulez une réponse API avec des données fictives
        $mockResponse = new MockResponse(json_encode([
            [
                'nom'  => 'Île-de-France',
                'code' => 11,
            ],
            [
                'nom'  => 'Provence-Alpes-Côte d\'Azur',
                'code' => 93,
            ],
        ]));

        // Configurez le client HTTP pour renvoyer la réponse simulée
        $this->httpClient->setResponseFactory(
            fn ($method, $url) => $mockResponse
        );

        // Appelez la méthode du service
        $regions = $this->geoApiService->getRegionData();

        // Testez que la méthode renvoie les données attendues
        $this->assertCount(2, $regions); // Il y a 2 régions simulées
        $this->assertEquals('Île-de-France', $regions[0]['nom']);
        $this->assertEquals(11, $regions[0]['code']);
    }

    public function testGetRegionDepartmentsData(): void
    {
        // Simulez une réponse API avec des départements fictifs
        $mockResponse = new MockResponse(json_encode([
            [
                'nom'  => 'Bouches-du-Rhône',
                'code' => 13,
            ],
            [
                'nom'  => 'Vaucluse',
                'code' => 84,
            ],
        ]));

        // Configurez le client HTTP pour renvoyer la réponse simulée
        $this->httpClient->setResponseFactory(
            fn ($method, $url) => $mockResponse
        );

        // Appelez la méthode du service
        $departments = $this->geoApiService->getRegionDepartmentsData(93);

        // Testez que la méthode renvoie les départements attendus
        $this->assertCount(2, $departments); // Il y a 2 départements simulés
        $this->assertEquals('Bouches-du-Rhône', $departments[0]['nom']);
        $this->assertEquals(13, $departments[0]['code']);
    }
}
