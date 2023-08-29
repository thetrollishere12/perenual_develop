<?php

namespace App\Http\Resources\ArticleFaq;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class ArticleFaqImageResource extends JsonResource
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
                "original_url" => Storage::disk('public')->url('article_faq/'.$this->folder.'/og.jpg')
            ];

        }else{

            return [
                // "image_id" => $this->id,
                "license" => $this->license,
                "license_name" => $this->license_name,
                "license_url" => $this->license_url,
                "original_url" => Storage::disk('public')->url('article_faq/'.$this->folder.'/og.jpg'),
                "regular_url" => Storage::disk('public')->url('article_faq/'.$this->folder.'/regular.jpg'),
                "medium_url" => Storage::disk('public')->url('article_faq/'.$this->folder.'/medium.jpg')
            ];

        }
    }
}
