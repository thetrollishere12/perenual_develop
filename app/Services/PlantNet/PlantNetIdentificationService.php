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

    /**
     * Identify plants using the PlantNet API.
     *
     * @param array $imageUrls An array of image URLs representing the same plant (max: 5 images)
     * @param array $parameters Optional parameters for the identification request.
     *                          Available parameters:
     *                          - include-related-images (boolean, default: false): Whether to include related images.
     *                          - no-reject (boolean, default: false): Disable "no result" in case of reject class match.
     *                          - lang (string, default: 'en'): Language for the identification results.
     *                          - type (string, default: 'kt'): Model type.
     *                          - authenix-access-token (string): Authenix access token.
     *                          - organs (array, default: ['auto']): Organs associated with images. If you have 2 images, the array should contain two organs. Available values : leaf, flower, fruit, bark, auto
     *
     * @return array The identified plant information.
     * @throws \Exception If an error occurs during the identification process.
     */
    public function identifyPlant(array $imageUrls, array $parameters = []): array
    {
        try {
            $request = Http::withHeaders($this->header);

            $include_related_images = $parameters["include-related-images"] ?? false;
            $no_reject = $parameters["no-reject"] ?? false;
            $lang = $parameters["lang"] ?? "en";
            $type = $parameters["type"] ?? "kt";
            $authenix_access_token = $parameters["authenix-access-token"] ?? null;
            $organs = $parameters["organs"] ?? null;

            if (!$organs) {
                $organs = array_fill(0, count($imageUrls), "auto");
            } else if (count($organs) !== count($imageUrls)) {
                throw new Exception("Number of organs in the array should correlate with the number of images sent.");
            }

            // Add optional parameters to the URL query string
            $payload = [
                'lang' => $lang,
                'include-related-images' => boolval($include_related_images) ? 'true' : 'false',
                'no-reject' => boolval($no_reject) ? 'true' : 'false',
                'type' => $type,
                'authenix-access-token' => $authenix_access_token,
            ];

            foreach ($imageUrls as $index => $imageUrl) {
                $request->attach('images', file_get_contents($imageUrl), "image_{$index}.jpeg");
                $request->attach('organs', $organs[$index]);
            }

            $queryParams = http_build_query($payload);
            $response = $request->post(
                "{$this->base_url}/v2/identify/all?api-key={$this->api_key}&{$queryParams}",
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

        return $result['results'];
        // return [
        //     'common_name' => $commonName,
        //     'scientific_name' => $scientificName,
        //     'probability' => $probability
        // ];
    }

    protected function handleError($result)
    {
        Log::error("PlantNet API Error: ".json_encode($result));

        throw new Exception($result);
    }
}