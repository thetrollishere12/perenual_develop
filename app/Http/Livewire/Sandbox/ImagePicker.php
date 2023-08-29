<?php

namespace App\Http\Livewire\Sandbox;

use Livewire\Component;
use App\Models\Species;
use WireUi\Traits\Actions;
class ImagePicker extends Component
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

        // Google Search

        // $this->google_image = GoogleImages($this->species->scientific_name[0])->items;

        // Scientific

        if($this->species->scientific_name[0]){

        $flickr = [FlickrImages($this->species->scientific_name[0],null)];

        if ($flickr[0]->pages > 100) {
            $flickr[0]->pages = 100;
        }

        for ($i=2; $i <= $flickr[0]->pages; $i++) { 
            $flickr[] = FlickrImages($this->species->scientific_name[0],$i);
        }

        foreach($flickr as $flick){

            foreach($flick->photo as $photo){

                $this->images[] = [
                    'url'=>'https://live.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_c.jpg',
                    'photo_id'=>$photo->id,
                    'photographer_id'=>$photo->owner
                ];

            }

        }


        // $unsplashes = [UnsplashImages($this->species->scientific_name[0],null)];

        // for ($i=2; $i <= $unsplashes[0]->total_pages; $i++) { 
        //     $unsplashes[] = UnsplashImages($this->species->scientific_name[0],$i);
        // }

        // foreach($unsplashes as $unsplash){

        //     foreach($unsplash->results as $photo){

        //         $this->images[] = [
        //             'url'=>$photo->urls->regular,
        //             'photographer'=>$photo->user->username,
        //             'photographer_id'=>$photo->user->id
        //         ];

        //     }

        // }

        $pixabays = [PixabayImages($this->species->scientific_name[0],null)];

        if ($pixabays[0]->totalHits > 20000) {
            $pixabays[0]->totalHits = 20000;
        }

        for ($i=2; $i <= ceil($pixabays[0]->totalHits/200); $i++) { 
            $pixabays[] = PixabayImages($this->species->scientific_name[0],$i);
        }

        foreach($pixabays as $pixabay){

            foreach($pixabay->hits as $photo){

                $this->images[] = [
                    'url'=>$photo->largeImageURL,
                    'photographer'=>$photo->user,
                    'photographer_id'=>$photo->user_id
                ];

            }

        }


        // $pexels = [PexelImages($this->species->scientific_name[0],null)];

        // for ($i=2; $i <= ceil($pexels[0]->total_results/$pexels[0]->per_page); $i++) { 
        //     $pexels[] = PexelImages($this->species->scientific_name[0],$i);
        // }

        // foreach($pexels as $pexel){

        //     foreach($pexel->photos as $photo){

        //         $this->images[] = [
        //             'url'=>$photo->src->large2x,
        //             'photographer'=>$photo->photographer,
        //             'photographer_id'=>$photo->photographer_id
        //         ];

        //     }

        // }

        }

    }

    public function more(){

        // $this->more = false;

        // Common Name

        if($this->species->common_name){

        if ($this->flickrPage > 1) {
            

            $flickr[] = FlickrImages($this->species->common_name,$this->flickrPage);
        

        }else{
            $flickr = [FlickrImages($this->species->common_name,null)];

            $this->flickrTotalPage = $flickr[0]->pages;

        }
        
        if ($this->flickrPage < $this->flickrTotalPage) {
            $this->flickrPage++;
        }

        foreach($flickr as $flick){

            foreach($flick->photo as $photo){

                $this->images[] = [
                    'url'=>'https://live.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_c.jpg',
                    'photo_id'=>$photo->id,
                    'photographer_id'=>$photo->owner
                ];

            }

        }

        

        if ($this->flickrPage > 1) {
            

            $flickr[] = FlickrImages($this->input,$this->flickrPage);
        

        }else{
            $flickr = [FlickrImages($this->input,null)];

            $this->flickrTotalPage = $flickr[0]->pages;

        }
        
        if ($this->flickrPage < $this->flickrTotalPage) {
            $this->flickrPage++;
        }

        foreach($flickr as $flick){

            foreach($flick->photo as $photo){

                $this->images[] = [
                    'url'=>'https://live.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_c.jpg',
                    'photo_id'=>$photo->id,
                    'photographer_id'=>$photo->owner
                ];

            }

        }


        // if ($this->pixabayPage > 1) {
            

        //     $pixabays[] = PixabayImages($this->species->common_name,$this->pixabayPage);
        

        // }else{
        //     $pixabays = [PixabayImages($this->species->common_name,null)];

        //     $this->pixabayTotalPage = $pixabays[0]->totalHits/200;

        // }
        
        // if ($this->pixabayPage < $this->pixabayTotalPage) {
        //     $this->pixabayPage++;
        // }

        // foreach($pixabays as $pixabay){

        //     foreach($pixabay->hits as $photo){

        //         $this->images[] = [
        //             'url'=>$photo->largeImageURL,
        //             'photographer'=>$photo->user,
        //             'photographer_id'=>$photo->user_id
        //         ];

        //     }

        // }

        // $unsplashes = [UnsplashImages($this->species->common_name,null)];

        // for ($i=2; $i <= $unsplashes[0]->total_pages; $i++) { 
        //     $unsplashes[] = UnsplashImages($this->species->common_name,$i);
        // }

        // foreach($unsplashes as $unsplash){

        //     foreach($unsplash->results as $photo){

        //         $this->images[] = [
        //             'url'=>$photo->urls->regular,
        //             'photographer'=>$photo->user->username,
        //             'photographer_id'=>$photo->user->id
        //         ];

        //     }

        // }

        // $pexels = [PexelImages($this->species->common_name,null)];

        // for ($i=2; $i <= ceil($pexels[0]->total_results/$pexels[0]->per_page); $i++) { 
        //     $pexels[] = PexelImages($this->species->common_name,$i);
        // }

        // foreach($pexels as $pexel){

        //     foreach($pexel->photos as $photo){

        //         $this->images[] = [
        //             'url'=>$photo->src->large2x,
        //             'photographer'=>$photo->photographer,
        //             'photographer_id'=>$photo->photographer_id
        //         ];

        //     }

        // }

        }

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

        Species::where('id',$this->species['id'])->update([
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

        Species::where('id',$this->species['id'])->update([
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

        Species::where('id',$this->species['id'])->update([
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

        $this->c_species = Species::where('id',$this->species['id'])->first();

        return view('livewire.sandbox.image-picker');
    }
}
