<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
class SpeciesIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'scientific_name',
        'common_name',
        'other_name',
        'family',
        'description',
        'effect',
        'solution',
        'host',
        'seen',
        'helpful',
        'image',
        'default_image',
        'folder',
        'copyright_images'
    ];

    protected $casts = [
        'image'=>'array',
        'copyright_images' => 'array',
        'other_name' => 'array',
        'host' => 'array',
        'effect' => 'array',
        'solution' => 'array',
        'description'=>'array'
    ];


    public function seen(){

            $type = "pest_disease";

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

                    SpeciesIssue::where('id',$this->id)->increment('seen');

                }

            }else{
             
                UniqueVisitor::create([
                    'type'=>$type,
                    'url'=>url()->current(),
                    'type_id'=>$this->id,
                    'user_id'=>(Auth::check())?Auth::user()->id:null,
                    'ip'=>\request()->ip()
                ]);

                SpeciesIssue::where('id',$this->id)->increment('seen');

            }

    }

}
