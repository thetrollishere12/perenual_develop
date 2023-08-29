<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
class SpeciesListSelectResource extends JsonResource
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
            'common_name' => $this->common_name,
            'scientific_name' => $this->scientific_name,
            'other_name' => $this->other_name,
            "regular_url" => Storage::disk('public')->url('species_image/'.$this->folder.'/regular/'.$this->default_image),
            "medium_url" => Storage::disk('public')->url('species_image/'.$this->folder.'/medium/'.$this->default_image),
            "small_url" => Storage::disk('public')->url('species_image/'.$this->folder.'/small/'.$this->default_image),
            "thumbnail" => Storage::disk('public')->url('species_image/'.$this->folder.'/thumbnail/'.$this->default_image),
        ];


    }
}
