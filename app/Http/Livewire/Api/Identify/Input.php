<?php

namespace App\Http\Livewire\Api\Identify;

use App\Services\PlantId\PlantIdentificationService;
use App\Services\PlantNet\PlantNetIdentificationService;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Input extends Component
{

    use WithFileUploads;


    public $image;
    // public $images;
    public $summary = [];

    public function updated()
    {

        $this->validate([
            'image' => 'image|max:10240', // 10MB Max
        ]);
        // store the image in the database
        $path = $this->image->store('images');
        $relative_url = Storage::url($path);
        $image_url = URL::to($relative_url);

        // Uncomment this part for multiple image support on the input component >>>>>>
        // $this->validate([
        //     'images.*' => 'image|max:10240', // 10MB Max for each image
        // ]);
        // $imageUrls = [];
        // foreach ($this->images as $image) {
        //     $path = $image->store('images');
        //     $relativeUrl = Storage::url($path);
        //     $imageUrl = URL::to($relativeUrl);
        //     $imageUrls[] = $imageUrl;
        // }
        // <<<<<<<

        // Resolve the PlantIdentificationService from the service container
        $plantIDService = app(PlantIdentificationService::class);
        $plantNetService = app(PlantNetIdentificationService::class);

        try {
            // Call the PlantID SDK
            // $this->summary = $plantNetService->identifyPlant([$image_url]);
            $this->summary = $plantIDService->identifyPlant([$image_url]);

        } catch(Exception $ex) {
            dd($ex->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.api.identify.input');
    }
}