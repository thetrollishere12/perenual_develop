<?php

namespace App\Http\Livewire\Admin\Propagation;

use Livewire\Component;

use App\Models\PropagationMethod;
use Livewire\WithPagination;

use WireUi\Traits\Actions;

class AddDescription extends Component
{
    use WithPagination;
    use Actions;

    public $descriptions;
    public $step;
    public $images;

    public function mount()
    {
        $this->refreshData();
    }

    public function updatedPage(){
        $this->refreshData();
    }

    private function refreshData(){
        $methods = PropagationMethod::paginate(1);

        $this->step =  [];

        foreach ($methods as $key => $step) {

            $this->images = $step->image;

            $this->descriptions[$step->id] = $step->description;

            if ($step->method && count($step->method) >  0) {
                $this->step[$step->id] = $step->method;
            } else {
                $this->step[$step->id][] = [
                    'image'   => '',
                    'subtitle'   => 'Step 1 - ',
                    'description'=> ''
                ];
            }
        }
    }


    public function render()
    {
        return view('livewire.admin.propagation.add-description', ['methods' => PropagationMethod::paginate(1)]);
    }

    public function add_step($id)
    {

        $increment = count($this->step[$id]) + 1;

        $this->step[$id][] = [
            'image'   => '',
            'subtitle'=>'Step '.$increment.' - ',
            'description'=>''
        ];
    }

    public function remove_step($id,$a_id)
    {
        unset($this->step[$id][$a_id]);
    }


    public function save_method($methodId)
    {

        PropagationMethod::where('id',$methodId)->update([
            'method'=>$this->step[$methodId]
        ]);
        $this->skipRender();
        return $this->notification([
            'title'       => 'Saved!',
            'description' => 'Method was successfully saved',
            'icon'        => 'success',
        ]);
    }


    public function save_description($id)
    {

        PropagationMethod::where('id',$id)->update([
            'description'=>$this->descriptions[$id]
        ]);
        $this->skipRender();
        return $this->notification([
            'title'       => 'Saved!',
            'description' => 'Description was successfully saved',
            'icon'        => 'success',
        ]);

    }


    public function saveImages($methodId)
    {

        PropagationMethod::where('id',$methodId)->update([
            'image'=>$this->images
        ]);
        $this->skipRender();
        return $this->notification([
            'title'       => 'Saved!',
            'description' => 'Image was successfully saved',
            'icon'        => 'success',
        ]);

    }

}