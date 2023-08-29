<?php

namespace App\Http\Livewire\Search\Species;

use Livewire\Component;
use Rogervila\ArrayDiffMultidimensional;
use App\Models\Country;
use Auth;

use App\Models\SpeciesSuggestedChange;

use WireUi\Traits\Actions;
class EditPopup extends Component
{
    use Actions;
    public $queries;


    public $editModal;

    public $old;
    public $new;

    public $show_zone = false;
 
    public $zone = [
        'min' => 3.00, // Targets handle 1 value
        'max' => 12.00 // Targets handle 2 value
    ];




    public $scientifics;
    public $others;
    public $countries;


    protected $listeners = ['deleteScientific','deleteOther'];

    public function mount(){

        $this->countries = Country::all()->pluck('name');
        $this->old = [
            'common_name' => $this->queries['common_name'],
            'scientific_name' => $this->queries['scientific_name'],
            'other_name' => $this->queries['other_name'],
            'origin' => $this->queries['origin'],
            'cycle' => strtolower($this->queries['cycle']),
            'watering' => strtolower($this->queries['watering']),
            'flowers' => $this->queries['flowers'],
            'flowering_season' => strtolower($this->queries['flowering_season']),
            'color' => strtolower($this->queries['color']),
            'sunlight' => array_map('strtolower',$this->queries['sunlight']),
            'soil' => array_map('ucfirst',$this->queries['soil']),
            'cones' => $this->queries['cones'],
            'fruits' => $this->queries['fruits'],
            'fruit_color' => array_map('strtolower',$this->queries['fruit_color']),
            'fruiting_season' => strtolower($this->queries['fruiting_season']),
            'harvest_season' => strtolower($this->queries['harvest_season']),
            'leaf' => $this->queries['leaf'],
            'leaf_color' => $this->queries['leaf_color'],
            'edible_leaf' => $this->queries['edible_leaf'],
            'growth_rate' => strtolower($this->queries['growth_rate']),
            'maintenance' => strtolower($this->queries['maintenance']),
            'edible_fruit' => $this->queries['edible_fruit'],
            'medicinal' => $this->queries['medicinal'],
            'poisonous_to_humans' => $this->queries['poisonous_to_humans'],
            'poisonous_to_pets' => $this->queries['poisonous_to_pets'],
            'drought_tolerant' => $this->queries['drought_tolerant'],
            'salt_tolerant' => $this->queries['salt_tolerant'],
            'thorny' => $this->queries['thorny'],
            'invasive' => $this->queries['invasive'],
            'rare' => $this->queries['rare'],
            'tropical' => $this->queries['tropical'],
            'cuisine' => $this->queries['cuisine'],
            'indoor' => $this->queries['indoor'],
            'care_level' => strtolower($this->queries['care_level'])
        ];

        $this->new = $this->old;

        $this->zone = [
            'min'=>$this->queries['hardiness']['min'],
            'max'=>$this->queries['hardiness']['max']
        ];

        $this->options = [
            'start' => [$this->zone['min'],$this->zone['max']],
            'range' => [
                'min' =>  [1],
                'max' => [13]
            ],
            'connect' => !0,
            'step' => 1,
            'pips' => [
                'mode' => 'steps',
                'density' => 3
            ]
        ];

    }


    public function deleteScientific($key){
        unset($this->new['scientific_name'][$key]);
    }

    public function addScientifics(){

        $limit = 15;

        $validatedData = $this->validate([
            'scientifics' => 'required|string|max:200',
            'new.scientific_name' => 'array'
        ]);

        if (in_array($this->scientifics,$this->new['scientific_name'])) {
            $this->addError('scientific_name','Scientific name already exist');
        }elseif(count($this->new['scientific_name']) < $limit){
            $this->new['scientific_name'][] = $this->scientifics;
        }else{
            $this->addError('scientific_name','You can only have between 0-'.$limit.' scientific name');
        }

        $this->reset("scientifics");
        
    }

    public function deleteOther($key){
        unset($this->new['other_name'][$key]);
    }

    public function addOthers(){

        $limit = 15;

        $validatedData = $this->validate([
            'others' => 'required|string|max:200',
            'new.other_name' => 'array'
        ]);

        if (in_array($this->others,$this->new['other_name'])) {
            $this->addError('other_name','Other name already exist');
        }elseif(count($this->new['other_name']) < $limit){
            $this->new['other_name'][] = $this->others;
        }else{
            $this->addError('other_name','You can only have between 0-'.$limit.' other name');
        }

        $this->reset('others');
        
    }


    public function open(){

        if (!Auth::user()) {
        
            $this->dialog([
                'title' => 'Please Login',
                'description' => 'You must login to suggest changes',
                'icon' => 'info'
            ]);

        }else{
            $this->editModal = true;
        }

    }

    public function submit(){

        $differences = ArrayDiffMultidimensional::looseComparison($this->old,$this->new);

        foreach ($differences as $key => $difference) {
            
            SpeciesSuggestedChange::create([
                'user_id'=>Auth::user()->id,
                'species_id'=>$this->queries['id'],
                'column'=>$key,
                'old'=>(is_array($this->queries[$key]) || is_object($this->queries[$key]))?json_encode($this->queries[$key]): $this->queries[$key],
                'new'=>(is_array($this->new[$key]) || is_object($this->new[$key]))?json_encode($this->new[$key]): $this->new[$key]
            ]);

        }

        $differences = ArrayDiffMultidimensional::looseComparison($this->new, $this->old);

        foreach ($differences as $key => $difference) {
            
            SpeciesSuggestedChange::create([
                'user_id'=>Auth::user()->id,
                'species_id'=>$this->queries['id'],
                'column'=>$key,
                'old'=>(is_array($this->queries[$key]) || is_object($this->queries[$key]))?json_encode($this->queries[$key]): $this->queries[$key],
                'new'=>(is_array($this->new[$key]) || is_object($this->new[$key]))?json_encode($this->new[$key]): $this->new[$key]
            ]);

        }
        
        $this->notification([
            'title'       => 'Changes submitted!',
            'description' => 'You have successfully submitted your changes. We will look over your submission',
            'icon'        => 'success',
        ]);

        return $this->editModal = false;

    }

    public function render()
    {


        return view('livewire.search.species.edit-popup');
    }
}
