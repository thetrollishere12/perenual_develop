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

class WriterUpload extends Component
{

    use WithFileUploads;
    
    use Actions;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public $cardModal = false;

    public $article = [];

    public $state = 1;

    public $photo;

    public function updated()
    {

        $this->validate([
            'photo' => 'max:1024', // 1MB Max
        ]);
        
        $name = random_id('article-u-f-').'.csv';

        $this->photo->storeAs('admin_article/file_upload',$name,'local');

        set_time_limit(0);

        $file = fopen(Storage::disk('local')->path("admin_article/file_upload/".$name), "r");

        $all_data = array();
        
        while ( ($data = fgetcsv($file)) !==FALSE ) {
            $all_data[] = $data;
        }

        $this->article = [];

        foreach($all_data as $key => $data){

            if ($key == 0) {

                continue;

            }else{

                $this->article[] = [
                    'title' => $data[0],
                    'type' => $data[1],
                    'image_select' => $data[2],
                    'tags' => explode(",",$data[6])
                ];

            }

        }

    }

    public function delete_article($key){
        unset($this->article[$key]);
    }
    
    public function preview(){
        $this->cardModal = true;
    }

    
    public function deleteTags($key,$k){
        unset($this->article[$key]['tags'][$k]);
    }


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

    public function saving(){

        $this->validate([
            'article.*.title' => 'required|string|min:1|max:65',
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

            if (strtolower($article['type']) == 'title') {
                
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
                    
                $context = stream_context_create(
                    array(
                        "http" => array(
                            "header" => "User-Agent: ".$_SERVER['HTTP_USER_AGENT']
                        )
                    )
                );


                $folder_name = random_id('sa'.$article_db->id);

                Storage::disk('public')->put('article/'.$folder_name.'/og.jpg',file_get_contents(str_replace('_c.jpg','_b.jpg',$article_db->main_image),true,$context));

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
        
        return redirect('admin/article-writer-upload');

    }

    public function render()
    {
        return view('livewire.admin.article.writer-upload');
    }
}
