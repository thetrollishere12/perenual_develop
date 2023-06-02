<?php

namespace App\Http\Livewire\Api\Identify;

use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Http;



class Input extends Component
{

    use WithFileUploads;


    public $image;

    public function updated()
    {   

        $this->validate([
            'image' => 'image|max:10240', // 10MB Max
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Api-Key' => env('PLANTID_KEY'),
        ])->post('https://api.plant.id/v2/identify', [
            'images' => base64_encode(file_get_contents($this->image->getRealPath())),
        ]);

        dd(json_decode($response->body()));

    }

    public function render()
    {
        return view('livewire.api.identify.input');
    }
}
