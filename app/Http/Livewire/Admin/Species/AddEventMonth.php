<?php

namespace App\Http\Livewire\Admin\Species;

use Livewire\Component;
use App\Models\Species;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class AddEventMonth extends Component
{   

    public $fruiting_month;
    public $flowering_month;
    public $harvesting_month;
    use WithPagination;
    use Actions;
    public function description($id){

        Species::where('id',$id)->update([
            'flowering_month'=>$this->flowering_month[$id],
            'fruiting_month'=>$this->fruiting_month[$id],
            'harvesting_month'=>$this->harvesting_month[$id]
        ]);

        return $this->notification([
            'title'       => 'Saved!',
            'description' => 'Description was successfully saved',
            'icon'        => 'success',
        ]);

    }

    public function render()
    {

        $species = Species::paginate(10);

        // Species::where('flowering_month',null)->update([
        //     'flowering_month'=>'[]'
        // ]);

        // Species::where('fruiting_month',null)->update([
        //     'fruiting_month'=>'[]'
        // ]);

        // Species::where('harvesting_month',null)->update([
        //     'harvesting_month'=>'[]'
        // ]);

        foreach ($species as $key => $value) {
        
            $this->fruiting_month[$value->id] = array_map('strtolower',$value->fruiting_month);
            $this->flowering_month[$value->id] = array_map('strtolower',$value->flowering_month);
            $this->harvesting_month[$value->id] = array_map('strtolower',$value->harvesting_month);
        }

        return view('livewire.admin.species.add-event-month',[
            'species'=>$species
        ]);
    }
}
