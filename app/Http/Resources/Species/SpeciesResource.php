<?php

namespace App\Http\Resources\Species;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use App\Models\SpeciesImage;
use App\Http\Resources\Species\SpeciesImageResource;

use App\Http\Resources\Species\SpeciesImageCollection;


use Carbon\Carbon;
use App\Helper\EncoderHelper;

class SpeciesResource extends JsonResource
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
            'common_name' => $this->common_name,
            'scientific_name' => $this->scientific_name,
            'other_name' => $this->other_name,
            'family' => $this->family,
            'origin' => $this->origin,
            'type' => $this->type,
            'dimension' => $this->dimension,

            // v2
            'dimensions' => $this->dimensions,

            'cycle' => $this->cycle,
            'attracts' => $this->attracts,
            'propagation' => $this->propagation,
            'hardiness' => $this->hardiness,
                'hardiness_location'=>[
                    'full_url'=>"https://".$_SERVER['SERVER_NAME']."/api/hardiness-map?species_id=".$this->id."&size=og&key=".$request->key,
                    'full_iframe'=>"<iframe frameborder=0 scrolling=yes seamless=seamless width=1000 height=550 style='margin:auto;' src='https://".$_SERVER['SERVER_NAME']."/api/hardiness-map?species_id=".$this->id."&size=og&key=$request->key'></iframe>"
                ],
            'watering' => $this->watering,

            // v2
            'depth_water_requirement' => $this->depth_water_requirement,
            'volume_water_requirement' => $this->volume_water_requirement,
            'watering_period'=>$this->watering_period,
            'watering_general_benchmark' => [
                'value'=>$this->watering_general_benchmark,
                'unit'=>'days'
            ],
            'plant_anatomy'=>$this->plant_anatomy,




            'sunlight' => $this->sunlight,


            // v2
            // 'sunlight_duration'=>$this->sunlight_duration,
            'pruning_month' => $this->pruning_month,
            'pruning_count' => $this->pruning_count,
            'seeds'=>$this->seeds,




            'maintenance' => $this->maintenance,
            'care-guides' => "http://".$_SERVER['SERVER_NAME']."/api/species-care-guide-list?species_id=".$this->id."&key=".$request->key,
            'soil' => $this->soil,
            'growth_rate' => $this->growth_rate,
            'drought_tolerant' => $this->drought_tolerant,
            'salt_tolerant' => $this->salt_tolerant,
            'thorny' => $this->thorny,
            'invasive' => $this->invasive,
            'tropical' => $this->tropical,
            'indoor' => $this->indoor,
            'care_level' => $this->care_level,
                'pest_susceptibility' => $this->pest_susceptibility,
                    'pest_susceptibility_api' => 'Coming Soon',
            'flowers' => $this->flowers,
                'flowering_season' => $this->flowering_season,
                'flower_color' => $this->color,
            'cones' => $this->cones,
            'fruits' => $this->fruits,
            'edible_fruit' => $this->edible_fruit,
                    'edible_fruit_taste_profile'=>'Coming Soon',
                    'fruit_nutritional_value'=>'Coming Soon',
            'fruit_color' => $this->fruit_color,
                // 'fruiting_season' => ($request->subscription->count() > 0) ? $this->fruiting_season : "Upgrade Plan For Access ".url('subscription-api-pricing').". Im sorry",

            // V2
            'harvest_season' => $this->harvest_season,
                // 'harvest_method'=>'Coming Soon',

            'leaf' => $this->leaf,
            'leaf_color' => $this->leaf_color,
            'edible_leaf' => $this->edible_leaf,
                    // 'edible_leaf_taste_profile'=>'Coming Soon',
                    // 'leaf_nutritional_value'=>'Coming Soon',
            'cuisine' => $this->cuisine,
                    // 'cuisine_list' => 'Coming Soon',
                'medicinal' => $this->medicinal,
                    // 'medicinal_use' => "Coming Soon",
                    // 'medicinal_method' => "Coming",
                'poisonous_to_humans' => $this->poisonous_to_humans,
                    // 'poison_effects_to_humans'=>'Coming Soon',
                    // 'poison_to_humans_cure' =>'Coming Soon',
                'poisonous_to_pets' => $this->poisonous_to_pets,
                    // 'poison_effects_to_pets'=>'Coming Soon',
                    // 'poison_to_pets_cure' =>'Coming Soon',
                    // 'rare' => 'Coming Soon',
                    // 'rare_level' => 'Coming Soon',
                    // 'endangered' => 'Coming Soon',
                    // 'endangered_level' => 'Coming Soon',
                    'description' => $this->description,
                    // 'problem' =>  'Coming Soon',
            'default_image' => new SpeciesImageResource(SpeciesImage::where('species_id',$this->id)->first()),


            // For Supreme Users let them have access to other things 
            'other_images' => ($request->subscription->count() > 0 && $request->subscription->first()['name'] == 'supreme api') ? new SpeciesImageCollection(SpeciesImage::where('species_id',$this->id)->get()->slice(1)) : "Upgrade Plan To Supreme For Access ".url('subscription-api-pricing').". Im sorry"

        ];

    }
}
