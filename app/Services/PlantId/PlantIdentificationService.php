<?php

namespace App\Services\PlantId;

use Exception;
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

    /**
     * Identify a plant from an image URL with optional parameters.
     *
     * @param string $imageUrl The URL of the image to identify.
     * @param array $options Optional parameters for the identification process.
     *                       Possible options:
     *                       - latitude (float): Geographic coordinate to increase identification accuracy.
     *                       - longitude (float): Geographic coordinate to increase identification accuracy.
     *                       - modifiers (array): List of strings that influence the identification process and results.
     *                       - plant_details (array): List of strings that determines the included information about the plant.
     *                       - plant_language (string): Language code (ISO 639-1) used for language-dependent plant details.
     *                       - plant_languages (array): List of up to 3 language codes (ISO 639-1) for language-dependent plant details.
     *                       - custom_id (int): Unique identifier for your purpose.
     *                       - datetime (int): Timestamp in seconds.
     *                       - identification_timeout (int): Timeout limit in seconds for the identification process.
     *                                                     If exceeded, the identification info without any suggestion is returned.
     *
     * @return array Identified plant information.
     *
     * @throws Exception if there is an error during the identification process.
     */
    public function identifyPlant(array $imageUrls, array $options = []): array
    {
        try {
            $latitude = $options['latitude'] ?? null;
            $longitude = $options['longitude'] ?? null;
            $modifiers = $options['modifiers'] ?? ["crops_fast", "similar_images"];
            $plantDetails = $options['plant_details'] ?? null;
            $plantLanguage = $options['plant_language'] ?? "en";
            $plantLanguages = $options['plant_languages'] ?? null;
            $customId = $options['custom_id'] ?? null;
            $datetime = $options['datetime'] ?? null;
            $identificationTimeout = $options['identification_timeout'] ?? null;

            // Build the request payload
            $payload = [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'modifiers' => $modifiers,
                'plant_details' => $plantDetails,
                'plant_language' => $plantLanguage,
                'plant_languages' => $plantLanguages,
                'custom_id' => $customId,
                'datetime' => $datetime,
                'identification_timeout' => $identificationTimeout
            ];


            $imageEncodings = collect($imageUrls)->map(function($imageUrl) {
                $base64Image = $this->encodeImageToBase64($imageUrl);
                return $base64Image;
            });

            $response = Http::withHeaders($this->header)->post(
                "{$this->base_url}/v2/identify",
                [
                    "images" => $imageEncodings,
                    ...$payload
                ]
            );

            $result = $response->json();
            if ($response->clientError() || $response->serverError()) {
                return $this->handleError($response->body());
            }

            // Process the result and return the identified plant information
            return $this->processResult($result);
        } catch (\Exception $e) {
            Log::error("Wahala:  ". json_encode($e));
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

        return $result;
        // return [
        //     'common_name' => $commonName,
        //     'scientific_name' => $scientificName,
        //     'probability' => $probability
        // ];
    }

    protected function handleError($result)
    {
        Log::error("Plant ID API Error Response :  ". $result);

        throw new Exception($result);
    }

    protected function encodeImageToBase64(string $imageUrl): string
    {
        $imageData = file_get_contents($imageUrl);
        return base64_encode($imageData);
    }
}
