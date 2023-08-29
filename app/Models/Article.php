<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Metrics\Chartable;
use Carbon\Carbon;
use Auth;

class Article extends Model
{
    use Chartable;
    use HasFactory,AsSource;

    /**
     * @var array
     */
    protected $fillable = [
        'publish_id',
        'parent_id',
        'main_image',
        'image_path',
        'title',
        'description',
        'tags',
        'seen',
        'helpful',
        'status'
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    public function childs(){
        return $this->hasMany(Article::class,'parent_id','id');
    }

    public function seen(){

            $type = "article";

            $unique = UniqueVisitor::where('ip',\request()->ip())->where('type_id',$this->id)->where('type',$type)->first();

            if ($unique) {
                
                if (Carbon::parse($unique->created_at)->addDays(1)->isPast()) {
                 
                    UniqueVisitor::create([
                        'type'=>$type,
                        'url'=>url()->current(),
                        'type_id'=>$this->id,
                        'user_id'=>(Auth::check())?Auth::user()->id:null,
                        'ip'=>\request()->ip()
                    ]);

                    Article::where('id',$this->id)->increment('seen');

                }

            }else{
             
                UniqueVisitor::create([
                    'type'=>$type,
                    'url'=>url()->current(),
                    'type_id'=>$this->id,
                    'user_id'=>(Auth::check())?Auth::user()->id:null,
                    'ip'=>\request()->ip()
                ]);

                Article::where('id',$this->id)->increment('seen');

            }
        

    }

}
