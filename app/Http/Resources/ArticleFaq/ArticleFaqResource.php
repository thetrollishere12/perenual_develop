<?php

namespace App\Http\Resources\ArticleFaq;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use App\Models\ArticleFaqImage;
use App\Http\Resources\ArticleFaq\ArticleFaqImageResource;

class ArticleFaqResource extends JsonResource
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
            'question' => $this->question,
            'answer' => $this->answer,
            'tags' => $this->tags,
            'default_image' => new ArticleFaqImageResource(ArticleFaqImage::where('article_id',$this->id)->first())
        ];
    }
}
