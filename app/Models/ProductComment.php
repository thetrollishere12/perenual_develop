<?php

namespace App\Models;

use App\Models\ProductCommentsLikes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductComment extends Model
{
    use HasFactory;

    protected $guarded=[];
    
    public function childs(){
        return $this->hasMany(ProductComment::class,  'parent_id','id');
    }

    public function productCommentsLikes(){
        return $this->hasMany(ProductCommentsLikes::class,'product_comment_id','id');
    }

    public function productReviews(){
        return $this->hasMany(ProductReview::class,'product_comment_id','id');
    }
}
