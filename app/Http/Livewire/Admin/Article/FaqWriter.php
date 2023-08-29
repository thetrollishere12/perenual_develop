<?php

namespace App\Http\Livewire\Admin\Article;

use Livewire\Component;
use App\Models\Species;
use App\Models\SpeciesIssue;
use App\Models\ArticleFaq;
use WireUi\Traits\Actions;
use Auth;
use Storage;
use Intervention\Image\Facades\Image;
use App\Models\ArticleFaqImage;
use Illuminate\Support\Facades\Http;

use App\Models\UniqueVisitor;
use Carbon\Carbon;
use ImageOptimizer;
use Spatie\ImageOptimizer\OptimizerChainFactory;
class FaqWriter extends Component
{   
    use Actions;

    public $day = 7;
    public $tags = [];
    public $tag;
    public $question;
    public $answer;
    public $suggested_tags = [];

    public $image_select = [];

    public $images = [];

    public $image_url;

    public $page = 1;

    protected $queryString = [
        'day' => ['except' => 0]
    ];

    protected $listeners = ['addTags','deleteTags','clickTag','image','shipping'];

    public function generate_answer(){

        $this->validate([
            'question' => 'required|string|min:5|max:255'
        ]);

        try{

        $this->answer  .= ltrim(AiGenerateText('Write a paragraph answer with line breaks for'.$this->question.'.',[]));

        }catch(\Exception $e){
            return $this->addError('error', 'There was a generating error. Please try again');
        }

    }

    public function suggested(){
        
        $this->suggested_tags = [];

        $species = Species::where(function ($query){
            
            foreach(explode(" ",$this->question) as $key){
                if (in_array($key,['what','which','when','where','who','whom','whose','why','whether','and','how','to','do','is','can','did','have','had','would','should','could',"what's","isn't","don't","aren't","wasn't","haven't","doesn't","an","a","should","you"]) || strlen($key) <= 4) {
                    continue;
                }else{
                    $query->orWhere('common_name','like','%'.$key.'%');
                }
                
            }

        })->limit(20)->get(['common_name'])->unique('common_name')->toArray();

        foreach($species as $species){
            $this->suggested_tags[] = $species['common_name'];
        }

        $issue = SpeciesIssue::where(function ($query){
            
            foreach(explode(" ",$this->question) as $key){
                if (in_array($key,['what','which','when','where','who','whom','whose','why','whether','and','how','to','do','is','can','did','have','had','would','should','could',"what's","isn't","don't","aren't","wasn't","haven't","doesn't","an","a","should","you"]) || strlen($key) <= 4) {
                    continue;
                }else{
                    $query->orWhere('common_name','like','%'.$key.'%');
                }
                
            }

        })->limit(20)->get(['common_name'])->unique('common_name')->toArray();

        foreach($issue as $issue){
            $this->suggested_tags[] = $issue['common_name'];
        }

    }

    public function deleteTags($key){
        unset($this->tags[$key]);
    }

    public function clickTag($string){

        $limit = 15;

        $string = trim($string);

        if (in_array($string,$this->tags)) {
            $this->addError('tags','Tags already exist');
        }elseif(count($this->tags) < $limit){
            $this->tags[] = $string;
        }else{
            $this->addError('tags','You can only have between 0-'.$limit.' Tags');
        }
            
    }

    public function addTags(){

        $limit = 15;

        $validatedData = $this->validate([
            'tag' => 'required|string|max:25',
            'tags' => 'array'
        ]);

        $this->tag = trim($this->tag);

        if (in_array($this->tag,$this->tags)) {
            $this->addError('tags','Tags already exist');
        }elseif(count($this->tags) < $limit){
            $this->tags[] = $this->tag;
        }else{
            $this->addError('tags','You can only have between 0-'.$limit.' Tags');
        }

        $this->reset('tag');
        
    }

    public function image_select($url){

        $this->image_select = [strtok($url, '?')];

    }

    public function image_url_paste(){
        $this->image_select = [strtok($this->image_url, '?')];
    }

    public function delete_image(){
        $this->image_select = [];
    }

    private function load_images(){

        if (!$this->image_select) {

            $this->images = [];

            if($this->tags){

                $search = "";

                foreach ($this->tags as $tag) {

                    $search .= $tag." ";

                }
                
                $unsplashes = [UnsplashImages($search,$this->page)];

                foreach($unsplashes as $unsplash){

                    foreach($unsplash->results as $photo){

                        $this->images[] = [
                            'url'=>$photo->urls->regular,
                            'photographer'=>$photo->user->username,
                            'photographer_id'=>$photo->user->id
                        ];

                    }

                }

                $pexels = [PexelImages($search,$this->page)];

                foreach($pexels as $pexel){

                    foreach($pexel->photos as $photo){

                        $this->images[] = [
                            'url'=>$photo->src->large2x,
                            'photographer'=>$photo->photographer,
                            'photographer_id'=>$photo->photographer_id
                        ];

                    }

                }

                $flickr = [FlickrImages($search,$this->page)];

                foreach($flickr as $flick){

                    foreach($flick->photo as $photo){

                        $this->images[] = [
                            'url'=>'https://live.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_c.jpg',
                            'photo_id'=>$photo->id,
                            'photographer_id'=>$photo->owner
                        ];

                    }

                }

            }

        }

    }

    public function load(){

        $this->page++;
        $this->load_images();

    }

    public function save(){

        $this->validate([
            'image_select' => 'required',
            'question' => 'required|string|min:5|max:255',
            // 'answer' => 'required|string|min:5|max:4000',
            'tags' => 'array|min:5|max:20'
        ]);

        $this->answer = ltrim(AiGenerateText('Write a paragraph answer for'.$this->question,['temperature'=>1]));

        $article = ArticleFaq::create([
            "publish_id"=>Auth::user()->id,
            "image"=> ($this->image_select)?$this->image_select[0]:null,
            "question"=>$this->question,
            "answer"=>$this->answer,
            "tags"=>$this->tags
        ]);

        $image_size = 1300;
        $medium_size = 500;

        $folder_name = random_id('faq_'.$article->id);

        try{

            Storage::disk('public')->put('article_faq/'.$folder_name.'/og.jpg',file_get_contents(str_replace('_c.jpg','_b.jpg',$article->image)));

            $disk = Storage::disk('public')->get('article_faq/'.$folder_name.'/og.jpg');

            $image = Image::make($disk);

            ($image->height() > $image->width()) ? $dimension = $image->width() : $dimension = $image->height();

            // Make large images into regular size
            if ($dimension > $image_size) {
                $img = $image->resize($image_size,$image_size, function($constraint){
                    $constraint->aspectRatio();
                })->stream();
                Storage::disk('public')->put('article_faq/'.$folder_name.'/regular.jpg', $img);
            }


            // fit image
            $img = $image->fit($dimension, $dimension, function($constraint){
                $constraint->upsize();
            });

            if ($dimension > $image_size) {
                $img->resize($image_size,$image_size);
            }

            $img->encode('jpg',80)->stream();

            Storage::disk('public')->put('article_faq/'.$folder_name.'/regular.jpg', $img);

            // medium image
            if ($dimension > $medium_size) {
                $img_tn = $image->fit($dimension, $dimension)->resize($medium_size,$medium_size,
                function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg',80)->stream();

                Storage::disk('public')->put('article_faq/'.$folder_name.'/medium.jpg', $img_tn);
            }else{
                Storage::disk('public')->put('article_faq/'.$folder_name.'/medium.jpg', $image);
            }

            ArticleFaq::find($article->id)->update([
                'image_path'=>'article_faq/'.$folder_name
            ]); 









                    if (str_contains($this->image_select[0],'flickr')) {
                    
                        $url = explode('_',basename($this->image_select[0]));

                        $info = FlickrImageGetInfo($url[0]);

                        $license_name =  null;
                        $license_url = null;

                        switch ($info->photo->license) {
                            case 4:
                                $license_name = "Attribution License";
                                $license_url = "https://creativecommons.org/licenses/by/2.0/";
                                break;
                            case 5:
                                $license_name = "Attribution-ShareAlike License";
                                $license_url = "https://creativecommons.org/licenses/by-sa/2.0/";
                                break;
                            case 6:
                                $license_name = "Attribution-NoDerivs License";
                                $license_url = "https://creativecommons.org/licenses/by-nd/2.0/";
                                break;
                            case 7:
                                $license_name = "No known copyright restrictions";
                                $license_url = "https://www.flickr.com/commons/usage/";
                                break;
                            case 9:
                                $license_name = "Public Domain Dedication (CC0)";
                                $license_url = "https://creativecommons.org/publicdomain/zero/1.0/";
                                break;
                            case 10:
                                $license_name = "Public Domain Mark";
                                $license_url = "https://creativecommons.org/publicdomain/mark/1.0/";
                                break;
                            default:
                                $license_name =  null;
                                $license_url = null;
                                break;
                        }

                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$this->image_select[0]
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>str_replace('_c.jpg','_b.jpg',$this->image_select[0]),
                            'folder'=>$folder_name,
                            'name'=>pathinfo(str_replace('_c.jpg','_b.jpg',$this->image_select[0]))['filename'].'.jpg',
                            'license' => $info->photo->license,
                            'license_name' =>$license_name,
                            'license_url'=>$license_url
                        ]);


                    }


                    if (str_contains($this->image_select[0],'wikimedia')) {
              
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$this->image_select[0]
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$this->image_select[0],
                            'folder'=>$folder_name,
                            'name'=>pathinfo($this->image_select[0])['filename'].'.jpg',
                            'license' => 45,
                            'license_name' =>'Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)',
                            'license_url'=>'https://creativecommons.org/licenses/by-sa/3.0/deed.en'
                        ]);

                    }

                    if (str_contains($this->image_select[0],'wikipedia')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$this->image_select[0]
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$this->image_select[0],
                            'folder'=>$folder_name,
                            'name'=>pathinfo($this->image_select[0])['filename'].'.jpg',
                            'license' => 45,
                            'license_name' =>'Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)',
                            'license_url'=>'https://creativecommons.org/licenses/by-sa/3.0/deed.en'
                        ]);

                    }

                    if (str_contains($this->image_select[0],'pexel')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$this->image_select[0]
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$this->image_select[0],
                            'folder'=>$folder_name,
                            'name'=>pathinfo($this->image_select[0])['filename'].'.jpg',
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }


                    if (str_contains($this->image_select[0],'pxhere')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$this->image_select[0]
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$this->image_select[0],
                            'folder'=>$folder_name,
                            'name'=>pathinfo($this->image_select[0])['filename'].'.jpg',
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }


                    if (str_contains($this->image_select[0],'plantnet')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$this->image_select[0]
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$this->image_select[0],
                            'folder'=>$folder_name,
                            'name'=>pathinfo($this->image_select[0])['filename'].'.jpg',
                            'license' => 44,
                            'license_name' =>'Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)',
                            'license_url'=>'https://creativecommons.org/licenses/by-sa/4.0/'
                        ]);

                    }


                    if (str_contains($this->image_select[0],'rawpixel')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$this->image_select[0]
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$this->image_select[0],
                            'folder'=>$folder_name,
                            'name'=>pathinfo($this->image_select[0])['filename'].'.jpg',
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }


                    if (str_contains($this->image_select[0],'pixabay')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$this->image_select[0]
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$this->image_select[0],
                            'folder'=>$folder_name,
                            'name'=>pathinfo($this->image_select[0])['filename'].'.jpg',
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }



                    ImageOptimizer::optimize(Storage::disk('public')->path('article_faq/'.$folder_name.'/og.jpg'));
                    ImageOptimizer::optimize(Storage::disk('public')->path('article_faq/'.$folder_name.'/regular.jpg'));
                    ImageOptimizer::optimize(Storage::disk('public')->path('article_faq/'.$folder_name.'/medium.jpg'));




            $this->notification([
                'title'       => 'Question Added',
                'description' => 'Question was added to the list',
                'icon'        => 'success'
            ]);

            $this->tags = [];
            $this->image_select = [];
            $this->suggested_tags = [];
            $this->images = [];
            $this->image_url = "";
            return $this->reset('question');

        }catch(\Exception $e){

            return $this->notification([
                'title'       => 'There was an error with Image',
                'description' => 'Please try again with a different photo',
                'icon'        => 'error'
            ]);

        }

    }

    public function render()
    {

        $species_of_the_day = UniqueVisitor::where('type','species')
        ->whereDate('created_at','<=',Carbon::today())
        ->whereDate('created_at','>=',Carbon::today()->subDays($this->day))
        ->select('type_id')->groupBy('type_id')->orderByRaw('COUNT(*) DESC')->limit(280)->pluck('type_id');

        $species = Species::whereIn('id',$species_of_the_day)
        ->paginate(40);

        return view('livewire.admin.article.faq-writer',[
            'queries'=>$species
        ]);
    }
}