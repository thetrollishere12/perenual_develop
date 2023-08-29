<?php

namespace App\Http\Resources\Disease;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use App\Models\DiseaseImage;
use App\Http\Resources\Disease\DiseaseImageResource;
use App\Http\Resources\Disease\DiseaseImageCollection;

class DiseaseListResource extends JsonResource
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
            'family' => $this->family,
            'description' => $this->description,
            'solution' => $this->solution,
            'host' => $this->host,
            // 'default_image' => new DiseaseImageResource(DiseaseImage::where('name','LIKE',"%".basename($this->default_image)."%")->first()),
            'images' => new DiseaseImageCollection(DiseaseImage::where('scientific_name','LIKE',"%".$this->scientific_name."%")->get())
        ];
    }
}
