<?php

namespace App\Http\Livewire\Profile\User\MyPlant;

use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;
use Intervention\Image\Facades\Image;
use App\Models\MyPlant;
use WireUi\Traits\Actions;
use Auth;
use Redirect;
use App\Models\Species;

class Create extends Component
{
    use Actions;
    use WithFileUploads;

    public $image_size = 1300;
    public $thumbnail_size = 600;

    public $image;
    public $displayImage = [];

    public $name;
    public $species;
    public $description;
    public $season;
    public $plant;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(){

        if ($this->plant) {

            foreach(Storage::disk('public')->files($this->plant['image'].'/og') as $img){
                $this->displayImage[] = [
                    "displayUrl" => Storage::disk('public')->url($this->plant['image'].'og/'.basename($img)),
                    "url" => Storage::disk('public')->url($img),
                    "name" => basename($img),
                    "original_name" => basename($img),
                    "default"=> ($this->plant['default_image'] == basename($img)) ? true : false,
                    "state"=>"permanent"
                ];
            }


            $this->name = $this->plant['name'];
            $this->species = json_encode($this->plant['species']);
            $this->description = $this->plant['description'];
            $this->season =  $this->plant['season'];
        }

    }

    public function clear_image(){
        $this->displayImage = [];
    }

    public function updated()
    {   

        $this->validate([
            'image' => 'image|max:10240', // 10MB Max
        ]);

        try{

            $disk = Storage::disk('public')->get('livewire-tmp/'.$this->image->getFilename());


            $image = Image::make($disk)->orientate();

            ($image->height() > $image->width()) ? $dimension = $image->width() : $dimension = $image->height();

            // Make large images into regular size
            if ($dimension > $this->image_size) {
                $img = $image->resize($this->image_size,$this->image_size, function($constraint){
                    $constraint->aspectRatio();
                })->stream();
                Storage::disk('public')->put('livewire-tmp/'.$this->image->getFilename(), $img);
            }

            // fit image
            $img = $image->fit($dimension, $dimension, function($constraint){
                $constraint->upsize();
            });

            if ($dimension > $this->image_size) {
                $img->resize($this->image_size,$this->image_size);
            }

            $img->encode('jpg',80)->stream();

            Storage::disk('public')->put('livewire-tmp/i-'.$this->image->getFilename(), $img);

            // thumbnail fit image
            if ($dimension > $this->thumbnail_size) {
                $img_tn = $image->fit($dimension, $dimension)->resize($this->thumbnail_size,$this->thumbnail_size,
                function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg',80)->stream();

                Storage::disk('public')->put('livewire-tmp/iq-'.$this->image->getFilename(), $img_tn);
            }
            // 

            $this->displayImage[0] = [
                "displayUrl" => Storage::disk('public')->url('livewire-tmp/iq-'.$this->image->getFilename().'?expires='.uniqid()),
                "url" => $this->image->temporaryUrl(),
                "name" =>'i-'.$this->image->getFilename(),
                "original_name" => $this->image->getFilename(),
                "state"=>"temporary"
            ];

        }catch(\Exception $e){
            return $this->addError('error', $e->getMessage());
        }

    }

    public function update(){

        $this->validate([
            'displayImage' => 'required|array',
            'species' => 'nullable|array',
            'name' => 'required|string|min:1|max:255',
            'description' => 'nullable|string|max:10000',
        ]);

        try{

            $species = Species::where('scientific_name','LIKE','%'.$this->species[0].'%')->first();

            MyPlant::where('user_id',Auth::id())->where('plant_id',$this->plant['plant_id'])->update([
                'common_name' => $species->common_name,
                'species' => $this->species,
                'season' => $this->season,
                'name' => $this->name,
                'description' => $this->description,
            ]);

            $directory = Storage::disk('public')->files($this->plant['image']);

            foreach($directory as $image){

                $exist = array_search(basename($image), array_column($this->displayImage, 'original_name'));

                if ($exist === false) {
                    // If image does not exist in array, delete the image from permanent folder
                    Storage::disk('public')->delete($image);
                    Storage::disk('public')->delete($this->plant['image'].'thumbnail/'.basename($image));
                }

            }

            // Moving displayImage from livewire temporary folder
            foreach($this->displayImage as $i => $images){

                $name = random_id('i-');

                if ($images['state'] == "temporary") {

                    // Moving thumbnail & fit photo
                    Storage::disk('public')->move('livewire-tmp/iq-'.$images['original_name'],$this->plant['image'].'thumbnail/'.$name.'.jpg');

                    Storage::disk('public')->move('livewire-tmp/'.$images['name'],$this->plant['image'].$name.'.jpg');

                    $images['name'] = $name.'.jpg';

                }elseif(isset($images['cropped']) && $images['cropped'] == "true" && $images['state'] == "permanent") {

                    // Moving thumbnail & fit photo
                    Storage::disk('public')->move('livewire-tmp/iq-'.$images['original_name'],$this->plant['image'].'thumbnail/'.$name.'.jpg');

                    Storage::disk('public')->move('livewire-tmp/'.$images['name'],$this->plant['image'].$name.'.jpg');

                    // Delete the outdated image from crop
                    Storage::disk('public')->delete($this->plant['image'].'thumbnail/'.$images['original_name']);
                    Storage::disk('public')->delete($this->plant['image'].$images['original_name']);
                    $images['name'] = $name.'.jpg';
                }
            }
            // Reconfigurate Default
        if(!in_array($this->plant['image'].$this->plant['default'],$directory)){
            $_default = false;
            // If default image has been changed to something else
            foreach($this->displayImage as $i => $images){

                if ($images['default'] == true) {
                    $_default = true;
                    MyPlant::where('plant_id',$this->plant['plant_id'])->where('user_id',Auth::id())->update([
                        'default_image'=>$images['name']
                    ]);
                    break;
                }

            }

            // If none is set to default auto makes the first one default
            if ($_default == false) {
                MyPlant::where('plant_id',$this->plant['plant_id'])->where('user_id',Auth::id())->update(['default_image'=>basename($directory[0])]);
            }

        }


            $this->notification([
                'title'       => 'Successfully Updated',
                'description' => 'Your plant has been updated and will be shared to the community!',
                'icon'        => 'success',
            ]);

            return redirect('user/my-plants');

        }catch(\Exception $e){
            dd($e);
            $this->notification([
                'title'       => 'There Was An Error',
                'description' => 'The info you have provided for your plant could not be saved. Please try again or contact us',
                'icon'        => 'error',
            ]);
        }

    }

    public function save(){

        $this->validate([
            'displayImage' => 'required|array',
            'species' => 'nullable|array',
            'name' => 'required|string|min:1|max:255',
            'description' => 'nullable|string|max:10000',
        ]);

        try{

            $plant_id = random_id('PLANT_');

            foreach($this->displayImage as $i => $image){

                // Move from temporary to permanent folder
                $random = random_id('i-');

                // Move to new temporary folder
                Storage::disk('public')->move('livewire-tmp/iq-'.$image['original_name'],'my-plant/'.Auth::id().'/'.$plant_id.'/medium/'.$random.'.jpg');

                Storage::disk('public')->move('livewire-tmp/'.$image['name'],'my-plant/'.Auth::id().'/'.$plant_id.'/og/'.$random.'.jpg');

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
            $plant->save();

            $this->notification([
                'title'       => 'Successfully Added',
                'description' => 'Your plant has been saved and will be shared to the community!',
                'icon'        => 'success',
            ]);

            return redirect('user/my-plants');

        }catch(\Exception $e){
            // dd($e);
            $this->notification([
                'title'       => 'There Was An Error',
                'description' => 'The info you have provided for your plant could not be saved. Please try again or contact us',
                'icon'        => 'error',
            ]);
        }

    }

    public function render()
    {
        return view('livewire.profile.user.my-plant.create');
    }
}
