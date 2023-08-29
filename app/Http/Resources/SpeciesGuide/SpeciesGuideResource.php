<?php

namespace App\Http\Resources\SpeciesGuide;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SpeciesGuide\SpeciesGuideSectionResource;
use App\Http\Resources\SpeciesGuide\SpeciesGuideSectionCollection;

class SpeciesGuideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'species_id' => $this->species()->first()->id,
            'common_name' => $this->common_name,
            'scientific_name' => $this->scientific_name,
            'section' => new SpeciesGuideSectionCollection($this->section($request->type)->get())
        ];
    }
}
