<?php

namespace App\Http\Resources\Species;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use App\Models\SpeciesImage;
use App\Http\Resources\Species\SpeciesImageResource;

class SpeciesListResource extends JsonResource
{
    public function toArray($request)
    {
        $array = [
            'id' => $this->id,
            'common_name' => $this->common_name,
            'scientific_name' => $this->scientific_name,
            'other_name' => $this->other_name,
        ];

        if ($request->subscription > 0 || $this->id <= $request->limit) {
            $array += [
                'cycle' => $this->cycle,
                'watering' => $this->watering,
                'sunlight' => $this->sunlight,
                'default_image' => new SpeciesImageResource(SpeciesImage::where('species_id',$this->id)->first())
            ];
        } else {
            $error_message = "Upgrade Plans To Premium/Supreme - " . url('subscription-api-pricing') . ". I'm sorry";
            $array += [
                'cycle' => $error_message,
                'watering' => $error_message,
                'sunlight' => $error_message,
                'default_image' => [
                    "license"=>451,
                    "license_name"=>"CC0 1.0 Universal (CC0 1.0) Public Domain Dedication",
                    "license_url"=>"https://creativecommons.org/publicdomain/zero/1.0/",
                    "original_url"=>Storage::disk("public")->url("image/upgrade_access.jpg"),
                    "regular_url"=>Storage::disk("public")->url("image/upgrade_access.jpg"),
                    "medium_url"=>Storage::disk("public")->url("image/upgrade_access.jpg"),
                    "small_url"=>Storage::disk("public")->url("image/upgrade_access.jpg"),
                    "thumbnail"=>Storage::disk("public")->url("image/upgrade_access.jpg")
                ],
            ];
        }

        return $array;
    }
}