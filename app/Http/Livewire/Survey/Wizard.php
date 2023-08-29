<?php

namespace App\Http\Livewire\Survey;

use Livewire\Component;

use Vildanbina\LivewireWizard\WizardComponent;
use App\Models\Survey;

use App\Steps\CareLevel;
use App\Steps\Cuisine;
use App\Steps\Cycle;
use App\Steps\Edible;
use App\Steps\Flower;
use App\Steps\Fruits;
use App\Steps\Indoor;
use App\Steps\Maintenance;
use App\Steps\Medicinal;
use App\Steps\PoisonousToPets;
use App\Steps\Rare;
use App\Steps\Start;
use App\Steps\Submit;
use App\Steps\Sunlight;
use App\Steps\Thorny;
use App\Steps\Tropical;
use App\Steps\Watering;

class Wizard extends WizardComponent
{
    // My custom class property
    public $analyzeId;
    
    public $components;

    public $fill;

    public $set_images;

    public $images = [];

    public $h1;

    public $p;

    /*
     * Will return App\Models\User instance or will create empty User (based on $userId parameter) 
     */

    public function model()
    {

        $steps = [];

        array_unshift($this->components, 'Start');

        array_push($this->components,'Submit');

        foreach ($this->components as $key => $component) {
            $steps[] = "App\Steps\\".$component;

            $this->images[$key] = $component;

            if ($this->set_images) {

                if (isset($this->set_images[strtolower($component)])) {
                    $this->images[$key] = $this->set_images[strtolower($component)];
                }

            }

        }

        $this->steps = $steps;
        return Survey::findOrNew($this->analyzeId);
    }

    public function saved(){

        $state = $this->state;

        if($this->fill){

            $state = array_merge($this->fill,$state);

        }
        
        // Email only enabled if submit step exist

        $data = Survey::create([
            'user_id'=>auth()->check() ? auth()->id() : null,
            'title'=>$this->h1,
            'data'=>(object)$state,
            'email'=>(in_array("App\Steps\Submit",$this->steps))?$state['email']:null
        ]);

        return redirect()->to('plant-survey-quiz-test/survey-result')->with('analyze',$data);

    }

}
