<?php

namespace App\Services\PlantNet;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlantNetIdentificationService
{
    protected string $base_url;
    protected string $api_key;
    protected array $header;

    public function __construct()
    {
        $this->api_key = config('services.plantnet.api_key');
        $this->base_url = config('services.plantnet.base_url');

        $this->header = [
           'Api-Key' => $this->api_key,
            'Content-Type' => 'application/json',
        ];
    }

    public function identifyPlant(string $imageUrl): array
    {
        try {
            $response = Http::withHeaders($this->header)
                            ->attach('images[]', fopen($imageUrl, 'r'))
                            ->attach('organs[]', 'auto')
                            ->post(
                                "{$this->base_url}/v2/identify/all?api-key={$this->api_key}&lang=en",
                            );

            $result = $response->json();
            if ($response->clientError() || $response->serverError()) {
                return $this->handleError($response->body());
            }

            // Process the result and return the identified plant information
            return $this->processResult($result);
        } catch (\Exception $e) {
            // Handle any errors that occur during the identification process
            return $this->handleError($e);
        }
    }

    protected function processResult($result)
    {
        // Extract the relevant information from the result and return it
        // For example, you can access the identified plant's common name and scientific name like this:
        Log::info("PlantNet API Result: ".json_encode($result));

        $commonName = $result['results'][0]['species']['commonNames'][0]['name'];
        $probability = $result['results'][0]['score'];
        $scientificName = $result['results'][0]['species']['scientificName']['nameWithAuthor'];

        return [
            'common_name' => $commonName,
            'scientific_name' => $scientificName,
            'probability' => $probability
        ];
    }

    protected function handleError($result)
    {
        Log::error("PlantNet API Error: ".json_encode($result));

        throw new Exception($result);
    }
}