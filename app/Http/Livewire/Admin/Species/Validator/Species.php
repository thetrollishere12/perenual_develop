<?php

namespace App\Http\Livewire\Admin\Species\Validator;

use Livewire\Component;
use App\Models\Species as Specie;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request;
use App\Models\SpeciesApprove;
use Auth;
use WireUi\Traits\Actions;

use App\Models\PropagationMethod;

class Species extends Component
{
    use Actions;
    public $species = [];
    public $comment;
    public $approved_user;
    public $propagation;

    public function mount(){


        $this->propagation = PropagationMethod::pluck('name')->map(function ($value) {
            return ucfirst($value);
        });

        $species = Specie::where('id',Request::query('id'))->first();

        $columnNames = array_diff(Schema::getColumnListing('species'), [
            'created_at',
            'updated_at',
            'image',
            'seen',
            'helpful',
            'tags',
            'contributed_user_id'
        ]);

        // $columnNames will contain an array of column names
        $this->species = [];


        foreach ($columnNames as $columnName) {

            if (is_array($species->$columnName)) {
                $this->species[$columnName] = array_map('ucfirst', $species->$columnName);
            } elseif (is_string($species->$columnName)) {
                $this->species[$columnName] = ucfirst($species->$columnName);
            } else {
                $this->species[$columnName] = $species->$columnName;
            }

            if ($columnName == 'hardiness') {
            
                $this->options = [
                    'start' => [$species->$columnName['min'],$species->$columnName['max']],
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

        }
        
        

    }

    public function approve(){

        SpeciesApprove::updateOrCreate([
            'species_id'=>$this->species['id'],
            'user_id'=>Auth::user()->id
        ],[
            'species_id'=>$this->species['id'],
            'user_id'=>Auth::user()->id,
            'comment'=>$this->comment
        ]);

        $this->notification([
            'title'       => 'Approved',
            'description' => 'Species has been approved',
            'icon'        => 'success',
        ]);

    }

    public function disapprove(){

        SpeciesApprove::where('species_id',$this->species['id'])->where('user_id',Auth::user()->id)->delete();

        $this->notification([
            'title'       => 'Disapproved',
            'description' => 'Species has been disapproved',
            'icon'        => 'error',
        ]);

    }

    public function save(){

        if (!is_array($this->species['scientific_name'])) {
            $this->species['scientific_name'] = explode(',',$this->species['scientific_name']);
        }

        if (!is_array($this->species['other_name'])) {
            $this->species['other_name'] = explode(',',$this->species['other_name']);
        }

        if (!is_array($this->species['origin'])) {
            $this->species['origin'] = explode(',',$this->species['origin']);
        }

        Specie::where('id',$this->species['id'])->update($this->species);

        $this->notification([
            'title'       => 'Saved',
            'description' => 'Species Details Has Been Saved',
            'icon'        => 'success',
        ]);

    }

    public function render()
    {
        $this->approved_user = SpeciesApprove::where('species_id',$this->species['id'])->where('user_id',Auth::user()->id)->get();
        return view('livewire.admin.species.validator.species');
    }
}
