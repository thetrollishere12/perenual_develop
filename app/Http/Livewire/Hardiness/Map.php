<?php

namespace App\Http\Livewire\Hardiness;

use Livewire\Component;
use App\Models\Species;

use Carbon\Carbon;
use App\Helper\EncoderHelper;

class Map extends Component
{

    public $zone = [
        'min'=>1,
        'max'=>13
    ];

    public $size;

    public $species_id;

    protected $queryString = [
        'species_id' => ['except' => '']
    ];

    public function mount(){

        if ($this->species_id) {

                $species = Species::findOrFail($this->species_id);

                $this->zone = [
                    'min' => (int)$species->hardiness['min'], // Targets handle 1 value
                    'max' => (int)$species->hardiness['max'] // Targets handle 2 value
                ];

        }
        // $check = api_key_check($this->key);

        // $subscription = is_subscribed($check->user_id);
   
        // if (!is_subscribed_to($check->user_id,'supreme api')) {
        //     $this->size = 'sm';
        // }else{
        //     $this->size = 'lg';
        // }


        if (!$this->size) {
            $this->size = 'lg';
        }

    }
    
    public function render()
    {
        return view('livewire.hardiness.map');
    }
}
