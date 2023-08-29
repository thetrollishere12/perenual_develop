<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;

class PropagationMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'method',
        'image',
        'default_image',
        'folder',
        'attributes',
        'tags'
    ];

    protected $casts = [
        'attributes' => 'array',
        'tags' => 'array',
        'image'=>'array',
        'method'=>'array'
    ];

    public function seen(){

            $type = "propagation";

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

                PropagationMethod::where('id',$this->id)->increment('seen');

            }
        

    }

}
