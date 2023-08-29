<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ArticleFaq;
use Storage;
use Intervention\Image\Facades\Image;
use App\Models\ArticleFaqImage;
use Illuminate\Support\Facades\Http;

use ImageOptimizer;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class ImageFaqPickerToFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ImageFaqPickerToFolder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get image url from faq image to folder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $image_size = 1300;
        $medium_size = 500;
        $small_size = 350;
        $thumbnail_size = 200;

        $articles = ArticleFaq::where('image','!=',null)->get();

        foreach ($articles as $k => $article) {

                    $folder_name = random_id('faq_'.$article->id);

                    $name = pathinfo(str_replace('_c.jpg','_b.jpg',$article->image))['filename'].'.jpg';

                    $context = stream_context_create(
                        array(
                            "http" => array(
                                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                            )
                        )
                    );

                    try{

                    Storage::disk('public')->put('article_faq/'.$folder_name.'/og.jpg',file_get_contents(str_replace('_c.jpg','_b.jpg',$article->image),true,$context));

                    }catch(\Exception $e){
                        // dd($e);
                        ArticleFaq::find($article->id)->update([
                            'image'=>null
                        ]); 

                        continue;

                    }


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









                    if (str_contains($article->image,'flickr')) {
                    
                        $url = explode('_',basename($article->image));

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
                            'origin_url'=>$article->image
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>str_replace('_c.jpg','_b.jpg',$article->image),
                            'folder'=>$folder_name,
                            'name'=>pathinfo(str_replace('_c.jpg','_b.jpg',$article->image))['filename'].'.jpg',
                            'license' => $info->photo->license,
                            'license_name' =>$license_name,
                            'license_url'=>$license_url
                        ]);


                    }


                    if (str_contains($article->image,'wikimedia')) {
              
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$article->image
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$article->image,
                            'folder'=>$folder_name,
                            'name'=>pathinfo($article->image)['filename'].'.jpg',
                            'license' => 45,
                            'license_name' =>'Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)',
                            'license_url'=>'https://creativecommons.org/licenses/by-sa/3.0/deed.en'
                        ]);

                    }

                    if (str_contains($article->image,'wikipedia')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$article->image
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$article->image,
                            'folder'=>$folder_name,
                            'name'=>pathinfo($article->image)['filename'].'.jpg',
                            'license' => 45,
                            'license_name' =>'Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)',
                            'license_url'=>'https://creativecommons.org/licenses/by-sa/3.0/deed.en'
                        ]);

                    }

                    if (str_contains($article->image,'pexel')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$article->image
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$article->image,
                            'folder'=>$folder_name,
                            'name'=>pathinfo($article->image)['filename'].'.jpg',
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }


                    if (str_contains($article->image,'pxhere')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$article->image
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$article->image,
                            'folder'=>$folder_name,
                            'name'=>pathinfo($article->image)['filename'].'.jpg',
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }


                    if (str_contains($article->image,'plantnet')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$article->image
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$article->image,
                            'folder'=>$folder_name,
                            'name'=>pathinfo($article->image)['filename'].'.jpg',
                            'license' => 44,
                            'license_name' =>'Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)',
                            'license_url'=>'https://creativecommons.org/licenses/by-sa/4.0/'
                        ]);

                    }


                    if (str_contains($article->image,'rawpixel')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$article->image
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$article->image,
                            'folder'=>$folder_name,
                            'name'=>pathinfo($article->image)['filename'].'.jpg',
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }


                    if (str_contains($article->image,'pixabay')) {
                    
                        ArticleFaqImage::updateOrCreate([
                            'origin_url'=>$article->image
                        ],[
                            'article_id'=>$article->id,
                            'origin_url'=>$article->image,
                            'folder'=>$folder_name,
                            'name'=>pathinfo($article->image)['filename'].'.jpg',
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }





                    ImageOptimizer::optimize(Storage::disk('public')->path('article_faq/'.$folder_name.'/og.jpg'));
                    ImageOptimizer::optimize(Storage::disk('public')->path('article_faq/'.$folder_name.'/regular.jpg'));
                    ImageOptimizer::optimize(Storage::disk('public')->path('article_faq/'.$folder_name.'/medium.jpg'));










        }

    }
}
