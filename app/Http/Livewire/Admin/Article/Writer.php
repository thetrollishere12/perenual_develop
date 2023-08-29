<?php

namespace App\Http\Livewire\Admin\Article;

use Livewire\Component;
use App\Models\Species;
use App\Models\SpeciesIssue;
use App\Models\Article;
use App\Models\ArticleSection;
use WireUi\Traits\Actions;
use Auth;
use Storage;
use Intervention\Image\Facades\Image;

use Livewire\WithFileUploads;

class Writer extends Component
{

    use Actions;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public $cardModal = false;

    public $article = [];

    public $state = 1;

    

    public function preview(){
        $this->cardModal = true;
    }

    public function mount(){

        $this->article = [
            [
            'type' => 'title',
            'title' => ''
            ],
            [
            'type' => 'subtitle',
            'title' => ''
            ]
        ];

    }

    public function add(){
        $this->article[] = [
            'type' => 'subtitle',
            'title' => '',
        ];
    }

    public function delete_article($key){
        unset($this->article[$key]);
    }

    // public function clickTag_title($string){

    //     $limit = 15;

    //     $string = trim($string);

    //     if (in_array($string,$this->tags)) {
    //         $this->addError('tags','Tags already exist');
    //     }elseif(count($this->tags) < $limit){
    //         $this->tags[] = $string;
    //     }else{
    //         $this->addError('tags','You can only have between 0-'.$limit.' Tags');
    //     }
        
    // }




    // private function load_images_title(){

    //     if (!$this->image_select) {

    //         $this->images = [];
            
    //         if($this->tags){

    //             $search = "";

    //             foreach ($this->tags as $tag) {

    //                 $search .= $tag." ";

    //             }
                
    //             $unsplashes = [UnsplashImages($search,$this->article[$key]['page'])];

    //             foreach($unsplashes as $unsplash){

    //                 foreach($unsplash->results as $photo){

    //                     $this->images[] = [
    //                         'url'=>$photo->urls->regular,
    //                         'photographer'=>$photo->user->username,
    //                         'photographer_id'=>$photo->user->id
    //                     ];

    //                 }

    //             }

    //             $pexels = [PexelImages($search,$this->article[$key]['page'])];

    //             foreach($pexels as $pexel){

    //                 foreach($pexel->photos as $photo){

    //                     $this->images[] = [
    //                         'url'=>$photo->src->large2x,
    //                         'photographer'=>$photo->photographer,
    //                         'photographer_id'=>$photo->photographer_id
    //                     ];

    //                 }

    //             }

    //             $flickr = [FlickrImages($search,$this->article[$key]['page'])];

    //             foreach($flickr as $flick){

    //                 foreach($flick->photo as $photo){

    //                     $this->images[] = [
    //                         'url'=>'https://live.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_c.jpg',
    //                         'photo_id'=>$photo->id,
    //                         'photographer_id'=>$photo->owner
    //                     ];

    //                 }

    //             }

    //         }

    //     }

    // }

    // public function load_title(){

    //     $this->article[$key]['page']++;
    //     $this->load_images_title();

    // }

    // public function image_select_title($url){

    //     $this->image_select = [$url];

    // }

    // public function delete_image_title(){
    //     $this->image_select = [];
    // }









    public function deleteTags($key,$k){
        unset($this->article[$key]['tags'][$k]);
    }

    // public function clickTag($key,$string){

    //     $limit = 15;

    //     $string = trim($string);

    //     if (in_array($string,$this->subtitles[$key]['tags'])) {
    //         $this->addError('tags','Tags already exist');
    //     }elseif(count($this->subtitles[$key]['tags']) < $limit){
    //         $this->subtitles[$key]['tags'][] = $string;
    //     }else{
    //         $this->addError('tags','You can only have between 0-'.$limit.' Tags');
    //     }
            
    // }

    public function addTags($key){

        $limit = 20;

        $validatedData = $this->validate([
            'article.'.$key.'.tags' => 'array'
        ]);

        $tags = explode(',',trim($this->article[$key]['tag']));

        foreach($tags as $tag){


            if (in_array(trim(strtolower($tag)),$this->article[$key]['tags'])) {
                $this->addError('tags','Tags already exist');
            }elseif(count($this->article[$key]['tags']) < $limit){
                $this->article[$key]['tags'][] = trim(strtolower($tag));
            }else{
                $this->addError('tags','You can only have between 0-'.$limit.' Tags');
            }


        }

        return $this->article[$key]['tag'] = "";
        
    }

    public function image_select($key,$url){

        $this->article[$key]['image_select'] = $url;

    }

    public function addImage($key){
        $this->article[$key]['image_select'] = strtok($this->article[$key]['image_url'], '?');
        $this->article[$key]['image_url']="";
    }

    public function delete_image($key){
        $this->article[$key]['image_select'] = "";
    }

    public function load_images($key){

        if (!$this->article[$key]['image_select']) {

            $this->article[$key]['images'] = [];

            if($this->article[$key]['tags']){

                $search = "";

                foreach ($this->article[$key]['tags'] as $tag) {

                    $search .= $tag." ";

                }
                
                $unsplashes = [UnsplashImages($search,$this->article[$key]['page'])];

                foreach($unsplashes as $unsplash){

                    foreach($unsplash->results as $photo){

                        $this->article[$key]['images'][] = [
                            'url'=>$photo->urls->regular,
                            'photographer'=>$photo->user->username,
                            'photographer_id'=>$photo->user->id
                        ];

                    }

                }

                $pexels = [PexelImages($search,$this->article[$key]['page'])];

                foreach($pexels as $pexel){

                    foreach($pexel->photos as $photo){

                        $this->article[$key]['images'][] = [
                            'url'=>$photo->src->large2x,
                            'photographer'=>$photo->photographer,
                            'photographer_id'=>$photo->photographer_id
                        ];

                    }

                }

                $flickr = [FlickrImages($search,$this->article[$key]['page'])];

                foreach($flickr as $flick){

                    foreach($flick->photo as $photo){

                        $this->article[$key]['images'][] = [
                            'url'=>'https://live.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_c.jpg',
                            'photo_id'=>$photo->id,
                            'photographer_id'=>$photo->owner
                        ];

                    }

                }

            }

        }

    }

    public function loading($key){

        $this->article[$key]['page']++;
        return $this->load_images($key);

    }

    public function saving(){

        $this->validate([
            'article.*.title' => 'required|string|min:5|max:65',
            'article.*.image_select' => 'required',
            'article.*.tags' => 'required|array|min:3|max:20',
        ]);


        $check_tags = [];

        foreach($this->article as $article){

            foreach($article['tags'] as $sub_tag){
                $check_tags[] = $sub_tag;
            }

        }

        if (count($check_tags) != count(array_unique($check_tags))) {
            
            return $this->notification([
                'title'       => 'Duplicated Tags',
                'description' => 'There was Duplicated Tags Detected. Please remove it',
                'icon'        => 'error'
            ]);

        }


        foreach($this->article as $article){

            if ($article['type'] == 'title') {
                
                $article_db = Article::create([
                    "publish_id"=>Auth::user()->id,
                    "main_image"=> ($article['image_select'])?$article['image_select']:null,
                    "title"=>$article['title'],
                    // "description"=>ltrim(AiGenerateText('Write paragraphs intro for an article titled '.$article['title'].'.',['temperature'=>1])),
                    "tags"=>$article['tags']
                ]);

                $article_id = $article_db->id;

            }else{

                $article_db = Article::create([
                    "parent_id" => $article_id,
                    "publish_id"=>Auth::user()->id,
                    "main_image"=> ($article['image_select'])?$article['image_select']:null,
                    "title"=>$article['title'],
                    // "description"=>ltrim(AiGenerateText('Write paragraphs about '.$article['title'].' for an article titled '.$this->article[0]['title'].'.',['temperature'=>1])),
                    "tags"=>$article['tags']
                ]);

            }



            $image_size = 1300;
            $medium_size = 500;

            $folder_name = random_id('a_'.$article_db->id);

            try{
                    
                $folder_name = random_id('sa'.$article_db->id);

                Storage::disk('public')->put('article/'.$folder_name.'/og.jpg',file_get_contents(str_replace('_c.jpg','_b.jpg',$article_db->main_image)));

                $disk = Storage::disk('public')->get('article/'.$folder_name.'/og.jpg');

                $image = Image::make($disk);

                ($image->height() > $image->width()) ? $dimension = $image->width() : $dimension = $image->height();

                // Make large images into regular size
                if ($dimension > $image_size) {
                    $img = $image->resize($image_size,$image_size, function($constraint){
                        $constraint->aspectRatio();
                    })->stream();
                    Storage::disk('public')->put('article/'.$folder_name.'/regular.jpg', $img);
                }


                // fit image
                $img = $image->fit($dimension, $dimension, function($constraint){
                    $constraint->upsize();
                });

                if ($dimension > $image_size) {
                    $img->resize($image_size,$image_size);
                }

                $img->encode('jpg',80)->stream();

                Storage::disk('public')->put('article/'.$folder_name.'/regular.jpg', $img);

                // medium image
                if ($dimension > $medium_size) {
                    $img_tn = $image->fit($dimension, $dimension)->resize($medium_size,$medium_size,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg',80)->stream();

                    Storage::disk('public')->put('article/'.$folder_name.'/medium.jpg', $img_tn);
                }else{
                    Storage::disk('public')->put('article/'.$folder_name.'/medium.jpg', $image);
                }

                Article::find($article_db->id)->update([
                    'image_path'=>'article/'.$folder_name
                ]); 


            }catch(\Exception $e){

                return $this->notification([
                    'title'       => 'There was an error with Image',
                    'description' => 'Please try again with a different photo',
                    'icon'        => 'error'
                ]);

            }



        }
        

        $this->notification([
            'title'       => 'Article Added',
            'description' => 'Article was added to the list',
            'icon'        => 'success'
        ]);

        $this->state = 1;

        $this->article = [
            [
            'type' => 'title',
            'title' => ''
            ],
            [
            'type' => 'subtitle',
            'title' => ''
            ]
        ];
        
        return redirect('admin/article-writer');

    }

    public function render(){
        return view('livewire.admin.article.writer');
    }






    public function next(){


            if ($this->state == 1) {
                

                $this->validate([
                    'article.*.title' => 'required|string|min:5|max:65',
                ]);


                foreach($this->article as $key => $article){

                    $this->article[$key]['tags'] = [];
                    $this->article[$key]['tag'] = "";

                }



            }else{

                    
                $this->validate([
                    'article.*.tags' => 'required|array|min:3|max:20',
                ]);

                foreach($this->article as $key => $article){

                    $this->article[$key]['image_select'] = [];
                    $this->article[$key]['images']  = [];
                    // $this->article[$key]['image_url']  = "";
                    $this->article[$key]['page'] = 1;

                }




            }

        

        $this->state++;

    }



}
