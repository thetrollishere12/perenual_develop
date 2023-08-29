<?php

namespace App\Http\Livewire\Admin\Species;

use Livewire\Component;
use App\Models\Species;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class AddSpeciesDetails extends Component
{
    use WithPagination;
    use Actions;
    public $description;

    public function mount()
    {
        $this->refreshData();
    }

    public function updatedPage(){
        $this->refreshData();
    }

    private function refreshData(){

        $species = Species::paginate(10);

        $this->description = [];

        foreach ($species as $key => $species) {

            $this->description[$species->id] = $species->description;

        }
    }

    public function render()
    {
        return view('livewire.admin.species.add-species-details',[
            'species'=>Species::paginate(10)
        ]);
    }

    public function generate_description($id){

        $queries = Species::where('id',$id)->first();

        $this->description[$id] = ltrim(AiGenerateTextV3('Write a description for a plant species called'.$queries['common_name']." (".$queries['scientific_name'][0].') on why its amazing.',[],0));

    }

    public function submit($id){

        Species::where('id',$id)->update([
            'description'=>$this->description[$id]
        ]);

        return $this->notification([
                'title'       => 'Saved!',
                'description' => 'Description was successfully saved',
                'icon'        => 'success',
            ]);

    }

}
