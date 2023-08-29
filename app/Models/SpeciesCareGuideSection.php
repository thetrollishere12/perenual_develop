<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
class SpeciesCareGuideSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_id',
        'type',
        'description',
        'seen',
        'helpful',
        'generated_user_id'
    ];

    public function seen(){

            $type = "species_care_guide_section";

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

                    SpeciesCareGuideSection::where('id',$this->id)->increment('seen');

                }

            }else{
             
                UniqueVisitor::create([
                    'type'=>$type,
                    'url'=>url()->current(),
                    'type_id'=>$this->id,
                    'user_id'=>(Auth::check())?Auth::user()->id:null,
                    'ip'=>\request()->ip()
                ]);

                SpeciesCareGuideSection::where('id',$this->id)->increment('seen');

            }

    }

    public function generated_user(){

        return User::where('id',$this->generated_user_id);

    }

}
