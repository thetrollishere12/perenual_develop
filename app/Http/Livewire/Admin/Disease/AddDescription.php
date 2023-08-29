<?php

namespace App\Http\Livewire\Admin\Disease;

use Livewire\Component;

use App\Models\SpeciesIssue;
use Livewire\WithPagination;

use WireUi\Traits\Actions;

class AddDescription extends Component
{

    use WithPagination;
    use Actions;
    
    protected $diseases;
    public $descriptions;
    public $solution;
    public $effect;

    public function mount()
    {
        $this->refreshData();
    }

    public function updatedPage(){
        $this->refreshData();
    }

    private function refreshData(){
        $this->diseases = SpeciesIssue::paginate(1);

        $this->effect = [];
        $this->solution =  [];
        $this->descriptions =  [];

        foreach ($this->diseases as $key => $disease) {

            if ($disease->description && count($disease->description) >  0) {
                $this->descriptions[$disease->id] = $disease->description;
            } else {
                $this->descriptions[$disease->id][] = [
                    'subtitle'   => '',
                    'description'=> ''
                ];
            }

            if ($disease->effect && count($disease->effect) >  0) {
                $this->effect[$disease->id] = $disease->effect;
            } else {
                $this->effect[$disease->id][] = [
                    'subtitle'   => '',
                    'description'=> ''
                ];
            }

            if ($disease->solution && count($disease->solution) >  0) {
                $this->solution[$disease->id] = $disease->solution;
            } else {
                $this->solution[$disease->id][] = [
                    'subtitle'   => '',
                    'description'=> ''
                ];
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.disease.add-description',['diseases' => SpeciesIssue::paginate(1)]);
    }

    public function add_description($id)
    {
        $this->descriptions[$id][] = [
            'subtitle'=>'',
            'description'=>''
        ];
    }

    public function remove_description($id,$a_id)
    {
        unset($this->descriptions[$id][$a_id]);
    }
    
    public function save_description($id)
    {

        SpeciesIssue::where('id',$id)->update([
            'description'=>$this->descriptions[$id]
        ]);
        $this->skipRender();
        return $this->notification([
            'title'       => 'Saved!',
            'description' => 'Description was successfully saved',
            'icon'        => 'success',
        ]);

    }

    public function add_effect($id)
    {
        $this->effect[$id][] = [
            'subtitle'=>'',
            'description'=>''
        ];
    }

    public function remove_effect($id,$a_id)
    {
        unset($this->effect[$id][$a_id]);
    }

    public function save_effect($id)
    {

        SpeciesIssue::where('id',$id)->update([
            'effect'=>$this->effect[$id]
        ]);
        $this->skipRender();
        return $this->notification([
            'title'       => 'Saved!',
            'description' => 'Effect was successfully saved',
            'icon'        => 'success',
        ]);

    }

    public function add_solution($id)
    {
        $this->solution[$id][] = [
            'subtitle'=>'',
            'description'=>''
        ];
    }

    public function remove_solution($id,$a_id)
    {
        unset($this->solution[$id][$a_id]);
    }

    public function save_solution($id)
    {

        SpeciesIssue::where('id',$id)->update([
            'solution'=>$this->solution[$id]
        ]);
        $this->skipRender();
        return $this->notification([
            'title'       => 'Saved!',
            'description' => 'Solution was successfully saved',
            'icon'        => 'success',
        ]);

    }


}
