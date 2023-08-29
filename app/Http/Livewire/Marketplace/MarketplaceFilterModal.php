<?php

namespace App\Http\Livewire\Marketplace;

use Livewire\Component;

class MarketplaceFilterModal extends Component
{

    public $filterFormVisable = false;

    public $request = [];

    public $show_zone = false;

    protected $listeners = ['filter'];

    public function filter(){
        $this->filterFormVisable = true;
    }

    public function mount(){

        $this->zone = [
            'min'=>(isset($this->request['zmin'])) ? $this->request['zmin'] : 1.00,
            'max'=>(isset($this->request['zmax'])) ? $this->request['zmax'] : 13.00,
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

        $this->pet_friendly = (isset($this->request['pet_friendly'])) ? $this->request['pet_friendly'] : "";
        $this->poisonous = (isset($this->request['poisonous'])) ? $this->request['poisonous'] : "";
        $this->fruit = (isset($this->request['edible'])) ? $this->request['edible'] : "";
        $this->sort = (isset($this->request['sort'])) ? $this->request['sort'] : "";
        $this->shipping = (isset($this->request['shipping'])) ? 'free' : "";
        $this->cycle = (isset($this->request['cycle'])) ? $this->request['cycle'] : "";
        $this->sun_exposure = (isset($this->request['sun_exposure'])) ? $this->request['sun_exposure'] : "";
        $this->fruiting_season = (isset($this->request['fruiting_season'])) ? $this->request['fruiting_season'] : "";
        $this->flowering_season = (isset($this->request['flowering_season'])) ? $this->request['flowering_season'] : "";
        $this->location = (isset($this->request['location'])) ? $this->request['location'] : [];
        $this->soil = (isset($this->request['soil'])) ? $this->request['soil'] : [];
        $this->color = (isset($this->request['color'])) ? $this->request['color'] : [];

        $this->show_zone = (isset($this->request['show_zone'])) ? $this->request['show_zone'] : null;

    }
    
    public function render()
    {
        return view('livewire.marketplace.marketplace-filter-modal');
    }
}
