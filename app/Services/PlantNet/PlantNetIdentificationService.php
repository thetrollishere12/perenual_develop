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
    protected string $boundary;

    public function __construct()
    {
        $this->api_key = config('services.plantnet.api_key');
        $this->base_url = config('services.plantnet.base_url');

        // Generate a unique boundary string
        $this->boundary = uniqid();
        $this->header = [
            'Api-Key' => $this->api_key,
            'Accept' => 'application/json',
            // 'Content-Type' => 'multipart/form-data'. $this->boundary,
        ];
    }

    public function identifyPlant(array $imageUrls): array
    {
        try {
            $request = Http::withHeaders($this->header)->attach('organs', 'auto');

            foreach ($imageUrls as $index => $imageUrl) {
                $request->attach('images', file_get_contents($imageUrl), "image_{$index}.jpeg");
            }

            $response = $request->post(
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

        $commonName = $result['results'][0]['species']['commonNames'][0];
        $probability = $result['results'][0]['score'];
        $scientificName = $result['results'][0]['species']['scientificName'];

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