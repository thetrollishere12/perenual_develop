<?php

namespace App\Models\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SpeciesComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'species_id',
        'scientific_name',
        'parent_id',
        'comment',
        'user_id',
        'user_like'
    ];

    protected $casts = [
        'scientific_name' => 'array'
    ];

    public function childs(){
        return $this->hasMany(SpeciesComment::class,'parent_id','id');
    }

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function SpeciesCommentReview(){
        return $this->hasMany(SpeciesCommentReview::class,'species_comment_id','id');
    }

}
