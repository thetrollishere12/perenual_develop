<?php

namespace App\Http\Livewire\Profile\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;
use Intervention\Image\Facades\Image;

class Photo extends Component
{

    use WithFileUploads;

    public $image_size = 1300;
    public $thumbnail_size = 500;



    public $cropImageModalFormVisable = false;
    public $image;
    public $image_array = [];
    public $product;

    public $crop_key;

    protected $listeners = ['edit_image','save_new_cropped','init_crop','crop','postAdded'];

    public function updated()
    {   

        // For Multiple Images
        // $this->validate([
        //     'image.*' => 'image|max:1024', // 1MB Max
        // ]);
        
        $limit = 8;

        $this->validate([
            'image' => 'image|max:10240', // 10MB Max
        ]);

        try{

        if (count($this->image_array) < $limit) {

            // $img = Image::make($this->image);
            // $img->resize(300, 300,
            // function ($constraint) {
            //     $constraint->aspectRatio();
            // })->encode('png',80)->stream();

            // Storage::disk('public')->put('livewire-tmp/s-'.$this->image->getFilename(), $img);

            $disk = Storage::disk('public')->get('livewire-tmp/'.$this->image->getFilename());

            // $optimizerChain = OptimizerChainFactory::create();

            // $optimizerChain->optimize($url);


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
            }else{
                Storage::disk('public')->put('livewire-tmp/iq-'.$this->image->getFilename(), $image);
            }
            // 

            $this->image_array[] = [
                "displayUrl" => Storage::disk('public')->url('livewire-tmp/iq-'.$this->image->getFilename().'?expires='.uniqid()),
                "url" => $this->image->temporaryUrl(),
                "name" =>'i-'.$this->image->getFilename(),
                "original_name" => $this->image->getFilename(),
                "default"=> count($this->image_array) == 0 ? true : false,
                "state"=>"temporary"
            ];

        }else{
            $this->addError('tags','You can only have between 1-'.$limit.' Images');
        }

        }catch(\Exception $e){
            return $this->addError('error', $e->getMessage());
        }

    }

    public function default($id){
        
        foreach($this->image_array as $key => $image){
            $this->image_array[$key]['default'] = false;
        }
        $this->image_array[$id]['default'] = true;

    }

    public function open_cropper($key){
        $this->crop_key = $key;
        $this->dispatchBrowserEvent('openCropperModal',['image'=>$this->image_array[$key]]);
    }

    public function init_crop($crop){
        $this->crop = $crop;
    }

    public function crop(){

        $image = $this->image_array[$this->crop_key]['original_name'];


        // From marketplace folder
        if ($this->product && $this->image_array[$this->crop_key]['state'] == 'permanent') {
            $product = get_product($this->product->sku)->first();
            $disk = Storage::disk('public')->get($product->image.$image);
        // From livewire temporary folder
        }else{
            $disk = Storage::disk('public')->get('livewire-tmp/'.$image);
        }

        $img = Image::make($disk);

        ($img->height() > $img->width()) ? $dimension = $img->width() : $dimension = $img->height();

        $img->crop(round($this->crop['width']), round($this->crop['height']), round($this->crop['x']), round($this->crop['y']));

        if ($dimension > $this->image_size) {
            $img->resize($this->image_size,null, function($constraint){
                    $constraint->aspectRatio();
            });
        }else{
            $img->resize($dimension,null, function($constraint){
                    $constraint->aspectRatio();
            });
        }

        $img->encode('jpg',80)->stream();

        Storage::disk('public')->put('livewire-tmp/i-'.$image, $img);

        // thumbnail fit image
        if ($dimension > $this->thumbnail_size) {
            $disk = Storage::disk('public')->get('livewire-tmp/i-'.$image);

            $img_tn = $img->fit($dimension, $dimension)->resize($this->thumbnail_size,null,
            function ($constraint) {
                $constraint->aspectRatio();
            })->encode('jpg',80)->stream();

            Storage::disk('public')->put('livewire-tmp/iq-'.$image, $img_tn);
        }
        // 

        $this->image_array[$this->crop_key]['name'] = 'i-'.$image;
        $this->image_array[$this->crop_key]['displayUrl'] = Storage::disk('public')->url('livewire-tmp/iq-'.$image.'?expires='.uniqid());
        $this->image_array[$this->crop_key]['cropped'] = true;
        $this->dispatchBrowserEvent('closeCropperModal');
    }

    public function delete($id){

        unset($this->image_array[$id]);

        $exist = array_search(true, array_column($this->image_array, 'default'));

        if ($exist === false) {
            
            foreach($this->image_array as $key => $images){
                $this->image_array[$key]['default'] = true;
                break;
            }
            
        }
        
        // Check if a default image exist. If not its going to set the first in the array as default
        // if (array_search(true, array_column($this->image_array, 'default')) === false) {
        //     $this->image_array[array_key_first($this->image_array)]['default'] = true;
        // }

    }

    public function render()
    {
        $this->emit('image',$this->image_array);
        return view('livewire.profile.product.photo');
    }
}
