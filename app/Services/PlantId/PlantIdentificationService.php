<?php

namespace App\Services\PlantId;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlantIdentificationService
{
    protected string $base_url;
    protected string $api_key;
    protected array $header;

    public function __construct()
    {
        $this->api_key = config('services.plantid.api_key');
        $this->base_url = config('services.plantid.base_url');

        $this->header = [
            'Api-Key'       => $this->api_key,
            'Content-Type'  => 'application/json',
        ];
    }

    public function identifyPlant(string $imageUrl): array
    {
        try {
            $base64Image = $this->encodeImageToBase64($imageUrl);

            $response = Http::withHeaders($this->header)->post(
                "{$this->base_url}/v2/identify",
                [
                    "images"            => [$base64Image],
                    "modifiers"         => ["crops_fast", "similar_images"],
                    "plant_language"    => "en",
                ]
            );

            $result = $response->json();
            if ($response->clientError() || $response->serverError()) {
                return $this->handleError($result);
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
        Log::info("Plant ID API Result:  ". json_encode($result));

        // Extract the relevant information from the result and return it
        // For example, you can access the identified plant's common name and scientific name like this:
        $commonName = $result['suggestions'][0]['plant_name'];
        $probability = $result['suggestions'][0]['probability'];
        $scientificName = $result['suggestions'][0]['plant_details']['scientific_name'];

        return [
            'common_name' => $commonName,
            'scientific_name' => $scientificName,
            'probability' => $probability
        ];
    }

    protected function handleError($result): array
    {
        Log::error("Plant ID API Error Response :  ". json_encode($result));

        return [];
    }

    protected function encodeImageToBase64(string $imageUrl): string
    {
        $imageData = file_get_contents($imageUrl);
        return base64_encode($imageData);
    }
}
