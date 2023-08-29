<?php

namespace App\Http\Livewire\Api\Identify;

use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Http;
use Auth;


class Input extends Component
{

    use WithFileUploads;


    public $image;
    public $key;
    public $result = [];

    public function mount(){

        $this->key = "[YOUR-API-KEY]";

        if (Auth::user() && Auth::user()->api_key()->first()) {
            $this->key = Auth::user()->api_key()->first()->key;
        }

    }

    public function updated()
    {   

        $this->validate([
            'image.*' => 'image|max:10240', // 10MB Max
        ]);

        $url = [];
        foreach ($this->image as $image) {
            $url[] = $image->store('species_identify');
        }

        $this->result = plantIdentify($url,null);

    }

    public function render()
    {
        return view('livewire.api.identify.input');
    }
}
