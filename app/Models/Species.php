<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Carbon\Carbon;
use Auth;
class Species extends Model
{
    use HasFactory,AsSource, Filterable;

    protected $fillable = [
        'common_name',
        'scientific_name',
        'other_name',
        'family',
        'origin',
        'type',
        'dimension',
        'cycle',
        'watering',
        'edible_fruit',
        'attracts',
        'flowers',
        'flowering_season',
        'color',
        'sunlight',
        'cones',
        'fruits',
        'fruit_color',
        'fruiting_season',
        'growth_rate',
        'maintenance',
        'soil',
        'hardiness',
        'problem',
        'pest_susceptibility',
        'propagation',
        'poisonous_to_humans',
        'poisonous_to_pets',
        'medicinal',
        'harvest_season',
        'leaf',
        'leaf_color',
        'edible_leaf',
        'drought_tolerant',
        'salt_tolerant',
        'thorny',
        'invasive',
        'rare',
        'tropical',
        'cuisine',
        'indoor',
        'care_level',
        'description',
        'copyright_image',
        'copyright_image2',
        'description',
        'image',
        'default_image',
        'folder',
        'seen',
        'helpful',
        'contributed_user_id',
        'tags',



        'fruit_flavor_profile',
        'leaf_flavor_profile',
        'flower_flavor_profile',

        'fruiting_month',
        'harvesting_month',
        'flowering_month',

        'seeds',



        'plant_anatomy',
        'dimensions',

        // For in the summer in loam/regular soil for watering benchmark
        'watering_general_benchmark',
        'volume_water_requirement',
        'depth_water_requirement',
        'watering_period',

        'pruning_count',
        'pruning_month',

        'sunlight_period',
        'sunlight_duration',
    ];

    protected $casts = [

        
        'soil' => 'array',
        'hardiness' => 'array',
        'pest_susceptibility' => 'array',
        'other_name' => 'array',
        'scientific_name' => 'array',
        'propagation' => 'array',
        'origin' => 'array',
        'sunlight' => 'array',
        'image' => 'array',
        'attracts' => 'array',
        'fruit_color' => 'array',
        'leaf_color' => 'array',
        'tags' => 'array',
        'dimensions' => 'array',

        'flowers'=>'boolean',
        'cones'=>'boolean',
        'fruits'=>'boolean',
        'edible_fruit'=>'boolean',
        'leaf'=>'boolean',
        'edible_leaf'=>'boolean',
        'medicinal'=>'boolean',
        'drought_tolerant'=>'boolean',
        'salt_tolerant'=>'boolean',
        'invasive'=>'boolean',
        'rare'=>'boolean',
        'tropical'=>'boolean',
        'cuisine'=>'boolean',
        'indoor'=>'boolean',
        'thorny'=>'boolean',

        'fruit_flavor_profile'=>'array',
        'leaf_flavor_profile'=>'array',
        'flower_flavor_profile'=>'array',

        'fruiting_month'=>'array',
        'harvesting_month'=>'array',
        'flowering_month'=>'array',




        'volume_water_requirement'=>'array',
        'depth_water_requirement'=>'array',

        'plant_anatomy'=>'array',
        'sunlight_duration'=>'array',

        'pruning_month'=>'array',
        'pruning_count'=>'array',

    ];

    protected $allowedFilters = [
        'id',
        'common_name',
        'scientific_name',
        'other_name',
        'family',
        'origin',
        'type',
        'seen',
        'helpful'
    ];

    public function c_ratings(){

        $scientific_name = $this->scientific_name;

        $ratingComment = Comment\SpeciesCommentReview::where('species_id',$this->id)->get();

        return $ratings = [
            'count'=>$ratingComment->count(),
            'ratings'=>$ratingComment->average('ratings')
        ];

    }

    public function guide(){
        
        return SpeciesCareGuide::where('species_id',$this->id);

    }

    public function image_details($default_image){

        return SpeciesImage::where('name','LIKE',"%".basename($default_image)."%")->first();

    }

    public function approved(){

        return SpeciesApprove::where('species_id',$this->id);

    }

    public function seen(){

            $type = "species";

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

                    Species::where('id',$this->id)->increment('seen');

                }

            }else{
             
                UniqueVisitor::create([
                    'type'=>$type,
                    'url'=>url()->current(),
                    'type_id'=>$this->id,
                    'user_id'=>(Auth::check())?Auth::user()->id:null,
                    'ip'=>\request()->ip()
                ]);

                Species::where('id',$this->id)->increment('seen');

            }

    }

}
