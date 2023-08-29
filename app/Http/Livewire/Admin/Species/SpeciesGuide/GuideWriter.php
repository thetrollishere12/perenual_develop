<?php

namespace App\Http\Livewire\Admin\Species\SpeciesGuide;

use Livewire\Component;

use Carbon\Carbon;

use App\Models\Species;
use App\Models\SpeciesCareGuideSection;
use App\Models\SpeciesCareGuide;
use WireUi\Traits\Actions;

use Auth;

class GuideWriter extends Component
{

    use Actions;

    public $s_id;

    public $guide = [];

    public $type;
    public $description;

    public $watering;
    public $sunlight;

    public $option;

    public function mount($id){

        $this->s_id = $id;

        $this->option = ['watering', 'sunlight', 'pruning'];

        $this->guide();

    }

    public  function guide(){

        $queries = Species::where('id',$this->s_id)->first();

        if ($queries->guide()->first()) {
            $queries->care_guide = $queries->guide()->first()->section(null)->get()->unique('type');


            $this->guide = [];
            foreach ($queries->care_guide as $guide) {

                $generated_user = $guide->generated_user()->first();

                if ($generated_user) {
                    $user_id = $generated_user;
                } else {
                    $user_id = 0;
                }

                $this->guide[] = [
                    'id'=>$guide->id,
                    'type'=>$guide->type,
                    'description'=>$guide->description,
                    'user'=>$user_id
                ];


                if (in_array(strtolower($guide->type),$this->option)) {
                    
                    unset($this->option[array_search(strtolower($guide->type),$this->option)]);

                }
                
            }

        }

    }

    public function generate_component(){

        $queries = Species::where('id',$this->s_id)->first();

        $this->description = ltrim(AiGenerateTextV2('Write on how much & when for '.$this->type.' for a plant species called '.$queries['common_name']." (".$queries['scientific_name'][0].') in detail but dont make it long.',[],0));
        
        // ltrim(AiGenerateTextV2('Write paragraphs on how much & when for '.$this->type.' for a plant species called'.$queries['common_name']." (".$queries['scientific_name'][0].') with appropriate line break.',[],0));
        
    }

    public function generate_exist_component($key){

        $queries = Species::where('id',$this->s_id)->first();

        $this->guide[$key]['description'] = ltrim(AiGenerateTextV2('Write on how much & when for '.$this->guide[$key]['type'].' for a plant species called'.$queries['common_name']." (".$queries['scientific_name'][0].') in detail but dont make it long.',[],0));

    }

    public function submit(){

        $this->validate([
            'type'=>'required|string',
            'description' => 'required|string|max:3000', // 1MB Max
        ]);

        $queries = Species::where('id',$this->s_id)->first();

        $guide = SpeciesCareGuide::where('species_id',$this->s_id)->first();

        if(!$guide){
            
            $guide = SpeciesCareGuide::create([
                'species_id'=>$this->s_id,
                'common_name' => $queries['common_name'],
                'scientific_name' => $queries['scientific_name']
            ]);

        }


        if (SpeciesCareGuideSection::where('guide_id',$guide->id)->where('type',$this->type)->count() > 0) {

            return $this->notification([
                'title'       => 'This Already Exist!',
                'description' => 'Component Already Exist Please Try Again',
                'icon'        => 'error',
            ]);

        }else{

            $array = [
                'guide_id'=>$guide->id,
                'type' => $this->type,
                'description' =>$this->description,
                'generated_user_id'=>Auth::user()->id
            ];

            if($this->type != 'pruning'){
                
                $type = $this->type;

                Species::where('id',$this->s_id)->update([
                    $this->type => $this->$type
                ]);

            }

            SpeciesCareGuideSection::create($array);

            $this->reset(['type', 'description']);

            $this->guide();

            return $this->notification([
                'title'       => 'Created!',
                'description' => 'Component was successfully created',
                'icon'        => 'success',
            ]);

        }



    }

    public function save_watering(){

        Species::where('id',$this->s_id)->update([
            'watering'=>$this->watering
        ]);

        return $this->notification([
            'title'       => 'Saved!',
            'description' => 'Watering was saved',
            'icon'        => 'success',
        ]);

    }

    public function save_sunlight(){

        Species::where('id',$this->s_id)->update([
            'sunlight'=>$this->sunlight
        ]);

        return $this->notification([
            'title'       => 'Saved!',
            'description' => 'Sunlight was saved',
            'icon'        => 'success',
        ]);

    }

    public function submit_change($key){

        SpeciesCareGuideSection::where('id',$this->guide[$key]['id'])->update([
            'description'=>$this->guide[$key]['description'],
            'generated_user_id'=>Auth::user()->id
        ]);

        return $this->notification([
                'title'       => 'Saved!',
                'description' => 'Component was successfully saved',
                'icon'        => 'success',
            ]);

    }

    public function render()
    {

        $queries = Species::where('id',$this->s_id)->first();

        $this->watering = ucfirst($queries->watering);

        $this->sunlight = array_map('trim',array_map('strtolower',$queries->sunlight));


        $all_matches = [];

        foreach ($this->guide as $key => $care_guide) {

            // Initialize the matches array for each care_guide
            $matches = [];

            if($care_guide['type'] == 'watering'){

                // Frequency Amount In Days Or Weeks
                if(preg_match_all('/\b\d+\s*(days|day|weeks|week)\b/i', $care_guide['description'], $match)) {
                    $matches['watering']['frequency'] = $match[0];
                }

                // Mentions Time Period For Watering
                if(preg_match_all('/\b(summer|winter|spring|fall|January|February|March|April|May|June|July|August|September|October|November|December|month|warm|cold)\b/i', $care_guide['description'], $match)) {
                    $matches['watering']['time_period'] = $match[0];
                }

                // Mentions Watering Liquid Measurements
                if(preg_match_all('/\b\d+\s*(gallons|gallon|ml)\b/i', $care_guide['description'], $match)) {
                    $matches['watering']['liquid_measurement'] = $match[0];
                }

                // Mentions Watering Solid Measurements
                if(preg_match_all('/\b\d+\s*(inches|inch|cm|mm|meters|meter|feet|foot)\b|\ban\s*inch\b/i', $care_guide['description'], $match)) {
                    $matches['watering']['solid_measurement'] = $match[0];
                }
            }

            if($care_guide['type'] == 'sunlight'){

                // Amount of sunlight
                if(preg_match_all('/\b\d+(\s*(?:-|to)\s*\d+)?\s*hours\b/i', $care_guide['description'], $match)) {
                    $matches['sunlight']['sunlight_amount'] = $match[0];
                }

                // Type of sunlight
                if(preg_match_all('/\b(full sun|part sun\/part shade|part shade|filtered shade|full shade)\b/i', $care_guide['description'], $match)) {
                    $matches['sunlight']['sunlight_type'] = $match[0];
                }

                // Sunlight for time of the day
                if(preg_match_all('/\b(morning|time|morningtime|evening|afternoon|night|midnight)\b/i', $care_guide['description'], $match)) {
                    $matches['sunlight']['sunlight_time_of_day'] = $match[0];
                }
            }

            if($care_guide['type'] == 'pruning'){

                // Says When To Prune
                if(preg_match_all('/\b(?:spring|summer|winter|fall|January|February|March|April|May|June|July|August|September|October|November|December)\b/i', $care_guide['description'], $match)) {
                    $matches['pruning']['prune_when'] = $match[0];
                }

                // How Much To Prune Off
                if(preg_match_all('/\b(\d+(?:\/\d+)?|\d+(?:\.\d+)?%|\d+(?:\/\d+)?(?:-third))\b/i', $care_guide['description'], $match)) {
                    $matches['pruning']['prune_amount'] = $match[0];
                }

                // How Often To Prune
                if(preg_match_all('/\b(\d+\s*(?:time|times)|once|twice|every\s*\d+\s*(?:day|week|month))\b/i', $care_guide['description'], $match)) {
                    $matches['pruning']['prune_frequency'] = $match[0];
                }
            }

            // Add matches to all_matches, removing duplicates
            foreach ($matches as $key => $subMatches) {
                foreach ($subMatches as $subKey => $matchArray) {
                    $all_matches[$key][$subKey] = $all_matches[$key][$subKey] ?? [];
                    foreach($matchArray as $matchItem) {
                        if(!in_array($matchItem, $all_matches[$key][$subKey])) {
                            $all_matches[$key][$subKey][] = $matchItem;
                        }
                    }
                }
            }

        }


        return view('livewire.admin.species.species-guide.guide-writer',[
            'query'=>$queries,
            'all_matches'=>$all_matches
        ]);
    }

}
