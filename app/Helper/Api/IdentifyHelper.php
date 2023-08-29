<?php

use App\Models\SpeciesImage;
use App\Http\Resources\Species\SpeciesImageResource;
use App\Models\SpeciesIdentifyRecord;

function plantIdentify($url, $req)
{
    $array = [];
    $suggestions = [];
    $api_key = $req->key ?? '[YOUR-API-KEY]';
    $count = $req->count ?? 1;

    // Plant ID API Request
    $images = collect($url)->map(function ($url) {
        $base64Image = base64_encode(Storage::disk('public')->get($url));
        return $base64Image;
    });

    $plantIdResponse = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Api-Key' => env('PLANTID_KEY'),
    ])->post('https://api.plant.id/v2/identify', [
        'images' => $images,
        "modifiers" => ["crops_fast", "similar_images"],
    ]);

    $plantIdOutput = json_decode($plantIdResponse->body());

    if ($plantIdOutput->is_plant != false) {
        $suggestions['plid'] = $plantIdOutput->suggestions;

        foreach ($plantIdOutput->suggestions as $i => $suggestion) {
            $defaultImage = SpeciesImage::where('scientific_name', 'LIKE', "%" . $suggestion->plant_details->scientific_name . "%")->first();

            $array[] = [
                'score' => $suggestion->probability,
                'name' => $suggestion->plant_name,
                'scientific_name' => $suggestion->plant_details->scientific_name,
                'resources' => [
                    'perenual' => $defaultImage ? "http://" . $_SERVER['SERVER_NAME'] . "/plant-species-database-search-finder/species/" . $defaultImage->species_id : null,
                    'google' => 'https://www.google.com/search?q=' . str_replace(' ', '+', $suggestion->plant_details->scientific_name) . '&tbm=isch&tbs=il:cl&hl=en&sa=X&ved=0CAAQ1vwEahcKEwjAm8e05tX8AhUAAAAAHQAAAAAQAw&biw=1519&bih=722'
                ],
                'care-guides' => $defaultImage ? "http://" . $_SERVER['SERVER_NAME'] . "/api/species-care-guide-list?species_id=" . $defaultImage->species_id . "&key=" . $api_key : null,
                'details' => $defaultImage ? "http://" . $_SERVER['SERVER_NAME'] . "/api/species-care-guide-list?species_id=" . $defaultImage->species_id . "&key=" . $api_key : null,
                'default_image' => $defaultImage ? new SpeciesImageResource($defaultImage) : null
            ];
        }
    }

    // PlantNet API Request
    $plantNetResponse = Http::withHeaders([
        'Accept' => 'application/json',
        'Api-Key' => env('PLANTNET_KEY'),
    ]);

    foreach ($url as $i => $imageUrl) {
        $plantNetResponse->attach('images', Storage::disk('public')->get($imageUrl), "image_{$i}.jpeg");
        $plantNetResponse->attach('organs', 'auto');
    }

    $plantNetApiResponse = $plantNetResponse->post('https://my-api.plantnet.org/v2/identify/all?api-key=' . env('PLANTNET_KEY'));
    $plantNetOutput = json_decode($plantNetApiResponse->body());

    if (!isset($plantNetOutput->statusCode)) {
        $suggestions['plnt'] = $plantNetOutput->results;

        foreach ($plantNetOutput->results as $i => $result) {
            $defaultImage = SpeciesImage::where('scientific_name', 'LIKE', "%" . $result->species->scientificNameWithoutAuthor . "%")->first();

            $array[] = [
                'score' => $result->score,
                'name' => !empty($result->species->commonNames) ? $result->species->commonNames[0] : $result->species->scientificNameWithoutAuthor,
                'scientific_name' => $result->species->scientificNameWithoutAuthor,
                'resources' => [
                    'perenual' => $defaultImage ? "http://" . $_SERVER['SERVER_NAME'] . "/plant-species-database-search-finder/species/" . $defaultImage->species_id : null,
                    'google' => 'https://www.google.com/search?q=' . str_replace(' ', '+', $result->species->scientificNameWithoutAuthor) . '&tbm=isch&tbs=il:cl&hl=en&sa=X&ved=0CAAQ1vwEahcKEwjAm8e05tX8AhUAAAAAHQAAAAAQAw&biw=1519&bih=722'
                ],
                'care-guides' => $defaultImage ? "http://" . $_SERVER['SERVER_NAME'] . "/api/species-care-guide-list?species_id=" . $defaultImage->species_id . "&key=" . $api_key : null,
                'details' => $defaultImage ? "http://" . $_SERVER['SERVER_NAME'] . "/api/species-care-guide-list?species_id=" . $defaultImage->species_id . "&key=" . $api_key : null,
                'default_image' => $defaultImage ? new SpeciesImageResource($defaultImage) : null
            ];
        }
    }

    // Extract unique scientific names using array_column and array_unique
    $uniqueScientificNames = array_unique(array_column($array, 'scientific_name'));

    // Filtered array without similar scientific names
    $filteredArray = array_intersect_key($array, $uniqueScientificNames);

    // Sort the filtered array by score in descending order (highest score first)
    usort($filteredArray, function ($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    SpeciesIdentifyRecord::create([
        'user_id' => $req->user_id ?? null,
        'image' => $url,
        'suggestion' => $suggestions
    ]);

    return [
        'count' => count($filteredArray),
        'results' => $filteredArray
    ];
}