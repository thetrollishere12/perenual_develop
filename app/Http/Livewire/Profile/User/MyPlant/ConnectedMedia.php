<?php

namespace App\Http\Livewire\Profile\User\MyPlant;

use Livewire\Component;

use App\Models\InstagramConnectedAccount;
use Auth;
use Storage;
use Intervention\Image\Facades\Image;
use App\Models\MyPlant;
use App\Models\Species;
use WireUi\Traits\Actions;

class ConnectedMedia extends Component
{
    use Actions;
    public $shareModal = false;
    public $species;
    public $name;
    public $description;
    public $displayImage;
    public $season;
    public $plant;
    private $attribute;

    public function share($m){
        $this->shareModal = true;
        
        $this->attributes = $m;

        $this->displayImage = $m['media_url'];


    }

    public function  mount(){

        try{
            $this->ig = InstagramConnectedAccount::where('user_id',Auth::user()->id)->first();
        }catch(\Exception $e){

        }

    }

    public function submit_plant(){

        $image_size = 1300;
        $medium_size = 500;

        $this->validate([
            'displayImage' => 'required|string',
            'species' => 'nullable|array',
            'name' => 'required|string|min:1|max:255',
            'description' => 'nullable|string|max:10000',
        ]);

        try{

            $plant_id = random_id('PLANT_');

            // Move from temporary to permanent folder
            $random = random_id('i-');

            // Move to new temporary folder
            Storage::disk('public')->put('my-plant/'.Auth::id().'/'.$plant_id.'/og/'.$random.'.jpg',file_get_contents($this->displayImage,true));

            $disk = Storage::disk('public')->get('my-plant/'.Auth::id().'/'.$plant_id.'/og/'.$random.'.jpg');

            $image = Image::make($disk);

            ($image->height() > $image->width()) ? $dimension = $image->width() : $dimension = $image->height();


            // medium image
            if ($dimension > $medium_size) {
                $img_tn = $image->fit($dimension, $dimension)->resize($medium_size,$medium_size,
                function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg',80)->stream();

                Storage::disk('public')->put('my-plant/'.Auth::id().'/'.$plant_id.'/medium/'.$random.'.jpg', $img_tn);
            }else{
                Storage::disk('public')->put('my-plant/'.Auth::id().'/'.$plant_id.'/medium/'.$random.'.jpg', $image);
            }


            $species = Species::where('scientific_name','LIKE','%'.$this->species[0].'%')->first();

            $plant = new MyPlant;
            $plant->user_id = Auth::id();
            $plant->plant_id = $plant_id;
            $plant->common_name = $species->common_name;
            $plant->species = $this->species;
            $plant->season = $this->season;
            $plant->name = $this->name;
            $plant->default_image = $random.'.jpg';
            $plant->image = 'my-plant/'.Auth::id().'/'.$plant_id.'/';
            $plant->description = $this->description;
            $plant->attributes = $this->attributes;
            $plant->save();

            $this->notification([
                'title'       => 'Successfully Added',
                'description' => 'Your plant has been saved and will be shared to the community!',
                'icon'        => 'success',
            ]);

            $this->reset(['name','description']);

            return $this->shareModal = false;

        }catch(\Exception $e){
         
            $this->notification([
                'title'       => 'There Was An Error',
                'description' => 'The info you have provided for your plant could not be saved. Please try again or contact us',
                'icon'        => 'error',
            ]);
        }


    }

    public function render()
    {   

        try{

            if ($this->ig) {
                return view('livewire.profile.user.my-plant.connected-media',['media'=>ig_b_user_media($this->ig->account_id,$this->ig->token)]);
            }else{
                return view('livewire.profile.user.my-plant.connected-media',['media'=>[]]);
            }

        }catch(\Exception $e){
            return view('livewire.profile.user.my-plant.connected-media',['media'=>[]]);
        }

    }
}
