<?php

namespace App\Http\Livewire\Shop;

use Livewire\Component;
use App\Models\MyPlant;
use Auth;
use WireUi\Traits\Actions;

class Plant extends Component
{
    use Actions;
    public $plant;

    public function like(){

        if ($this->plant['user_id'] == Auth::id()) {
            return $this->notification([
                'title'       => 'Cannot liked!',
                'description' => 'As much as you really love your plant, you sadly cannot like your own post',
                'icon'        => 'error',
            ]);
        }

        $myplant = MyPlant::where('plant_id',$this->plant['plant_id'])->where('id',$this->plant['id'])->count();

        if ($myplant > 0) {
            $myplant->increment('likes');

            return $this->notification([
                'title'       => 'Plant liked!',
                'description' => 'You have liked their plant!',
                'icon'        => 'heart',
                'iconColor'   => 'text-negative-400'
            ]);

        }else{
            return $this->notification([
                'title'       => 'There was an error!',
                'description' => 'There was an error trying to like this. Please try again',
                'icon'        => 'error',
            ]);
        }

    }

    public function render()
    {
        return view('livewire.shop.plant');
    }
}
