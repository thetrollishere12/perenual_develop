<?php

namespace App\Http\Livewire\Hardiness;

use Livewire\Component;

class MapFilter extends Component
{

    public $zone = [
        'min' => 3, // Targets handle 1 value
        'max' => 12 // Targets handle 2 value
    ];

    public $size;

    public function mount(){

        if (!$this->size) {
            $this->size = 'lg';
        }

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
    
    public function render()
    {
        return view('livewire.hardiness.map-filter');
    }
}
