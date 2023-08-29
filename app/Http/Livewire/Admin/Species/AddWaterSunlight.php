<?php

namespace App\Http\Livewire\Admin\Species;

use Livewire\Component;

use App\Models\Species;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class AddWaterSunlight extends Component
{

     use WithPagination;
    use Actions;

    public $sunlight;
    public $sunlight_period;
    public $sunlight_duration;

    public $watering;
    public $watering_period;
    public $depth_water_requirement = [];
    public $volume_water_requirement = [];

    public function mount()
    {
        $this->refreshData();
    }

    private function refreshData(){

        $species = Species::paginate(10);

        $this->sunlight = [];
        $this->sunlight_period = [];
        $this->sunlight_duration = [];
        $this->watering = [];
        $this->watering_period = [];
        $this->depth_water_requirement = [];
        $this->volume_water_requirement = [];

        foreach ($species as $key => $species) {

            $this->sunlight[$species->id] = $species->sunlight;
            $this->sunlight_period[$species->id] = $species->sunlight_period;
            $this->sunlight_duration[$species->id] = $species->sunlight_duration;
            $this->watering[$species->id] = $species->watering;
            $this->watering_period[$species->id] = $species->watering_period;
            $this->volume_water_requirement[$species->id] = $species->volume_water_requirement;
            $this->depth_water_requirement[$species->id] = $species->depth_water_requirement;

        }
    }

    public function render()
    {
        return view('livewire.admin.species.add-water-sunlight',[
            'species'=>Species::paginate(5)
        ]);
    }


    public function submit($id){
dd($this->depth_water_requirement[$id]);
        Species::where('id',$id)->update([
            'sunlight'=>$this->sunlight[$id],
            'sunlight_period'=>$this->sunlight_period[$id],
            'sunlight_duration'=>$this->sunlight_duration[$id],
            'watering'=>$this->watering[$id],
            'watering_period'=>$this->watering_period[$id],
            'volume_water_requirement'=>$this->volume_water_requirement[$id],
            'depth_water_requirement'=>$this->depth_water_requirement[$id]
        ]);

        return $this->notification([
            'title'       => 'Saved!',
            'description' => 'Details was successfully saved',
            'icon'        => 'success',
        ]);

    }


}
