<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
class SpeciesCareGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'common_name',
        'scientific_name',
        'species_id'
    ];

    protected $casts = [
        'scientific_name' => 'array',
    ];

    public function seen(){

            $type = "species_care_guide";

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

                    SpeciesCareGuide::where('id',$this->id)->increment('seen');

                }

            }else{
             
                UniqueVisitor::create([
                    'type'=>$type,
                    'url'=>url()->current(),
                    'type_id'=>$this->id,
                    'user_id'=>(Auth::check())?Auth::user()->id:null,
                    'ip'=>\request()->ip()
                ]);

                SpeciesCareGuide::where('id',$this->id)->increment('seen');

            }

    }

    public function species(){
        
        return Species::where('id',$this->species_id);

    }

    public function section($type){
        
        if ($type) {
            return SpeciesCareGuideSection::where('guide_id',$this->id)->whereIn('type',explode(',',$type));
        }else{
            return $this->hasMany(SpeciesCareGuideSection::class,'guide_id','id');
        }

    }

}
