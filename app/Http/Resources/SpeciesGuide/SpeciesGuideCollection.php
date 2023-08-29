<?php

namespace App\Http\Resources\SpeciesGuide;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SpeciesGuideCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function paginationInformation($request){

        $paginated = $this->resource->toArray();

        return [
            'to' => $paginated['to'],
            'per_page' => $paginated['per_page'],
            'current_page' => $paginated['current_page'],
            'from' => $paginated['from'],
            'last_page' => $paginated['last_page'],
            'total' => $paginated['total']
        ];

    }
        
}
