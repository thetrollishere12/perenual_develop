<?php

namespace App\Services\PlantNet;

use Illuminate\Support\Facades\Log;

class PlantIdentificationService
{
    protected string $base_url;
    protected string $api_key;
    protected array $header;

    public function __construct()
    {
        $this->api_key = config('services.plantnet.api_key');
        $this->base_url = config('services.plantnet.base_url');

        $this->header = [
//            'Api-Key' => $this->api_key,
            'Content-Type' => 'application/json',
        ];
    }

    public function identifyPlant(string $imageUrl): array
    {
        try {
            $response = Http::post("{$this->base_url}/v2/identify/all", [
                'api-key' => $this->api_key,
                'organs' => ['auto'],
                'images' => [$imageUrl],
                'lang' => 'en'
            ])->withHeaders($this->header);

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
//        {
//            "query": {
//            "project": "string",
//            "images": [
//                        "string"
//                    ],
//            "organs": [
//                        "string"
//                    ],
//            "includeRelatedImages": true
//          },
//          "language": "string",
//          "preferedReferential": "string",
//          "switchToProject": "string",
//          "bestMatch": "string",
//          "results": [
//            {
//                "score": 0,
//              "species": {
//                "scientificNameWithoutAuthor": "string",
//                "scientificNameAuthorship": "string",
//                "scientificName": "string",
//                "genus": {
//                    "scientificNameWithoutAuthor": "string",
//                  "scientificNameAuthorship": "string",
//                  "scientificName": "string"
//                },
//                "family": {
//                    "scientificNameWithoutAuthor": "string",
//                  "scientificNameAuthorship": "string",
//                  "scientificName": "string"
//                },
//                "commonNames": [
//                    "string"
//                ]
//              },
//              "images": [
//                {
//                    "organ": "string",
//                  "author": "string",
//                  "license": "string",
//                  "date": {
//                    "timestamp": 0,
//                    "string": "string"
//                  },
//                  "citation": "string",
//                  "url": {
//                    "o": "string",
//                    "m": "string",
//                    "s": "string"
//                  }
//                }
//              ],
//              "gbif": {
//                "id": 0
//              }
//            }
//          ],
//          "remainingIdentificationRequests": 0,
//          "version": "string"
//        }
        // Extract the relevant information from the result and return it
        // For example, you can access the identified plant's common name and scientific name like this:
        Log::info("PlantNet API Result: ".json_encode($result));

        $commonName = $result['results'][0]['species']['commonNames'][0]['name'];
        $probability = $result['results'][0]['score'];
        $scientificName = $result['results'][0]['species']['scientificName']['nameWithAuthor'];

        return [
            'common_name' => $commonName,
            'scientific_name' => $scientificName,
        ];
    }

    protected function handleError($result): array
    {
//        400
        //Bad Request
        //
        //401
        //Unauthorized
        //
        //404
        //Species Not Found
        //
        //413
        //Payload Too Large
        //
        //414
        //URI Too Long
        //
        //415
        //Unsupported Media Type
        //
        //429
        //Too Many Requests
        //
        //500
        //Internal Server Error
        return [];
    }
}
