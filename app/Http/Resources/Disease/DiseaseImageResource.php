<?php

namespace App\Http\Resources\Disease;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class DiseaseImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->license == 6) {
                
            return [
                // "image_id" => $this->id,
                "license" => $this->license,
                "license_name" => $this->license_name,
                "license_url" => $this->license_url,
                "original_url" => Storage::disk('public')->url('species_disease/'.$this->folder.'/og/'.$this->name)
            ];

        }else{

            return [
                // "image_id" => $this->id,
                "license" => $this->license,
                "license_name" => $this->license_name,
                "license_url" => $this->license_url,
                "original_url" => Storage::disk('public')->url('species_disease/'.$this->folder.'/og/'.$this->name),
                "regular_url" => Storage::disk('public')->url('species_disease/'.$this->folder.'/regular/'.$this->name),
                "medium_url" => Storage::disk('public')->url('species_disease/'.$this->folder.'/medium/'.$this->name),
                "small_url" => Storage::disk('public')->url('species_disease/'.$this->folder.'/small/'.$this->name),
                "thumbnail" => Storage::disk('public')->url('species_disease/'.$this->folder.'/thumbnail/'.$this->name),
            ];

        }
    }
}
