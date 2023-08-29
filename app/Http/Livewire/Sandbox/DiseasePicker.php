<?php

namespace App\Http\Livewire\Sandbox;

use Livewire\Component;
use App\Models\SpeciesIssue;
use WireUi\Traits\Actions;

class DiseasePicker extends Component
{
    use Actions;
    public $species;
    public $images = [];

    public $c_species;

    public $request;

    public $google_image = [];

    public $more = true;

    public $flickrPage = 0;
    public $flickrTotalPage;

    public $pixabayPage = 0;
    public $pixabayTotalPage;

    public $url;

    public $input;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(){


    }

    public function more(){

       

    }

    public function save(){

        $this->validate([
            'url' => 'string|required|url',
        ]);

        if ($this->c_species->image) {

            if (count($this->c_species->image) == 8) {
                $this->reset('url');
                
                return $this->notification([
                    'title'       => 'Cannot Save!',
                    'description' => 'Surpassed limit',
                    'icon'        => 'x-circle',
                ]);
            }

        }

        if ($this->c_species->image == null) {
            $array = [];
            array_push($array,$this->url);
        }else{
            $array = $this->c_species->image;

            if(!in_array($this->url,$array)){
                array_push($array,$this->url);
            }else{
                $this->reset('url');
                return $this->notification([
                    'title'       => 'Cannot Save!',
                    'description' => 'Already Exist',
                    'icon'        => 'x-circle',
                ]);

            }
            
        }

        SpeciesIssue::where('id',$this->species['id'])->update([
            'image'=>str_replace('_c.jpg','_b.jpg',$array)
        ]);

        $this->notification([
            'title'       => 'Saved!',
            'description' => 'Image Url Saved',
            'icon'        => 'success',
        ]);

        $this->reset('url');

        $this->emit('refreshComponent');

    }

    public function remove($key){

        $array = $this->c_species->image;

        unset($array[$key]);

        SpeciesIssue::where('id',$this->species['id'])->update([
            'image'=>str_replace('_c.jpg','_b.jpg',$array)
        ]);

        $this->notification([
            'title'       => 'Image Removed!',
            'description' => 'Image was removed',
            'icon'        => 'x-circle',
        ]);

    }

    public function select($url){

        if ($this->c_species->image) {

            if (count($this->c_species->image) == 8) {
                return $this->notification([
                    'title'       => 'Cannot Save!',
                    'description' => 'Surpassed limit',
                    'icon'        => 'x-circle',
                ]);
            }

        }

        if ($this->c_species->image == null) {
            $array = [];
            array_push($array,$url);
        }else{
            $array = $this->c_species->image;

            if(!in_array($url,$array)){
                array_push($array,$url);
            }else{

                return $this->notification([
                    'title'       => 'Cannot Save!',
                    'description' => 'Already Exist',
                    'icon'        => 'x-circle',
                ]);

            }
            
        }

        SpeciesIssue::where('id',$this->species['id'])->update([
            'image'=>str_replace('_c.jpg','_b.jpg',$array)
        ]);

        $this->notification([
            'title'       => 'Saved!',
            'description' => 'Image Url Saved',
            'icon'        => 'success',
        ]);

        $this->emit('refreshComponent');

    }

    public function render()
    {

        $this->c_species = SpeciesIssue::where('id',$this->species['id'])->first();

        return view('livewire.sandbox.disease-picker');
    }
}
