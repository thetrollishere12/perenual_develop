<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SpeciesIssue;
use Storage;
use Intervention\Image\Facades\Image;
use App\Models\DiseaseImage;
use Illuminate\Support\Facades\Http;

use ImageOptimizer;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class DiseasePickerToFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DiseasePickerToFolder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $species = SpeciesIssue::where('image','!=',null)->where('default_image',null)->get();

        foreach ($species as $k => $type) {

            if ($type->image) {
                
                // if ($type->folder) {
                //     continue;
                // }

                $folder_name = strtolower(str_replace(' ','_',str_replace(["'","(",")","."],'',$type->id.'_'.$type->scientific_name)));           

                if (str_contains(head($type->image),'pixabay')) {

                    $array_img = $type->image;

                    foreach ($array_img as $key => $image) {
                        if (strpos($image,'pixabay') !== false) {
                            unset($array_img[$key]);
                        }
                    }
                    
                    SpeciesIssue::find($type->id)->update([
                        'image'=>$array_img
                    ]); 

                    if (!$type->image) {
                        continue;
                    }

                }

                SpeciesIssue::find($type->id)->update([
                    'default_image'=>pathinfo(basename(parse_url(str_replace('_c.jpg','_b.jpg',str_replace('%','',head($type->image))),PHP_URL_PATH)))['filename'].'.jpg',
                    'folder'=> $folder_name
                ]); 

                $array_img = $type->image;

                foreach($array_img as $key => $t_image){

                    if (str_contains($t_image,'pixabay')) {
                        continue;
                    }

                    $name = pathinfo(basename(parse_url(str_replace('_c.jpg','_b.jpg',str_replace('%','',$t_image)),PHP_URL_PATH)))['filename'].'.jpg';

                    $context = stream_context_create(
                        array(
                            "http" => array(
                                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                            )
                        )
                    );

                    try{

                    Storage::disk('public')->put('species_disease/'.$folder_name.'/og/'.$name,file_get_contents(str_replace('_c.jpg','_b.jpg',$t_image),true,$context));

                    }catch(\Exception $e){
             
                        unset($array_img[$key]);

                        SpeciesIssue::find($type->id)->update([
                            'image'=>$array_img
                        ]); 

                        continue;

                    }

                    $disk = Storage::disk('public')->get('species_disease/'.$folder_name.'/og/'.$name);

                    $image = Image::make($disk);

                    ($image->height() > $image->width()) ? $dimension = $image->width() : $dimension = $image->height();

                    // Make large images into regular size
                    if ($dimension > $image_size) {
                        $img = $image->resize($image_size,$image_size, function($constraint){
                            $constraint->aspectRatio();
                        })->stream();
                        Storage::disk('public')->put('species_disease/'.$folder_name.'/regular/'.$name, $img);
                    }


                    // fit image
                    $img = $image->fit($dimension, $dimension, function($constraint){
                        $constraint->upsize();
                    });

                    if ($dimension > $image_size) {
                        $img->resize($image_size,$image_size);
                    }

                    $img->encode('jpg',80)->stream();

                    Storage::disk('public')->put('species_disease/'.$folder_name.'/regular/'.$name, $img);

                    // medium image
                    if ($dimension > $medium_size) {
                        $img_tn = $image->fit($dimension, $dimension)->resize($medium_size,$medium_size,
                        function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode('jpg',80)->stream();

                        Storage::disk('public')->put('species_disease/'.$folder_name.'/medium/'.$name, $img_tn);
                    }else{
                        Storage::disk('public')->put('species_disease/'.$folder_name.'/medium/'.$name, $image);
                    }

                    // small image
                    if ($dimension > $small_size) {
                        $img_tn = $image->fit($dimension, $dimension)->resize($small_size,$small_size,
                        function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode('jpg',80)->stream();

                        Storage::disk('public')->put('species_disease/'.$folder_name.'/small/'.$name, $img_tn);
                    }else{
                        Storage::disk('public')->put('species_disease/'.$folder_name.'/small/'.$name, $image);
                    }

                    // thumbnail fit image
                    if ($dimension > $thumbnail_size) {
                        $img_tn = $image->fit($dimension, $dimension)->resize($thumbnail_size,$thumbnail_size,
                        function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode('jpg',80)->stream();

                        Storage::disk('public')->put('species_disease/'.$folder_name.'/thumbnail/'.$name, $img_tn);
                    }else{
                        Storage::disk('public')->put('species_disease/'.$folder_name.'/thumbnail/'.$name, $image);
                    }


                    ImageOptimizer::optimize(Storage::disk('public')->path('species_disease/'.$folder_name.'/og/'.$name));
                    ImageOptimizer::optimize(Storage::disk('public')->path('species_disease/'.$folder_name.'/regular/'.$name));
                    ImageOptimizer::optimize(Storage::disk('public')->path('species_disease/'.$folder_name.'/medium/'.$name));
                    ImageOptimizer::optimize(Storage::disk('public')->path('species_disease/'.$folder_name.'/small/'.$name));
                    ImageOptimizer::optimize(Storage::disk('public')->path('species_disease/'.$folder_name.'/thumbnail/'.$name));


                    if (str_contains($t_image,'flickr')) {
                    
                        $url = explode('_',basename($t_image));

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

                        DiseaseImage::updateOrCreate([
                            'origin_url'=>$t_image
                        ],[
                            'scientific_name'=>$type->scientific_name,
                            'origin_url'=>str_replace('_c.jpg','_b.jpg',$t_image),
                            'folder'=>$folder_name,
                            'name'=>$name,
                            'license' => $info->photo->license,
                            'license_name' =>$license_name,
                            'license_url'=>$license_url
                        ]);


                    }


                    if (str_contains($t_image,'wikimedia')) {
              
                        DiseaseImage::updateOrCreate([
                            'origin_url'=>$t_image
                        ],[
                            'scientific_name'=>$type->scientific_name,
                            'origin_url'=>$t_image,
                            'folder'=>$folder_name,
                            'name'=>$name,
                            'license' => 45,
                            'license_name' =>'Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)',
                            'license_url'=>'https://creativecommons.org/licenses/by-sa/3.0/deed.en'
                        ]);

                    }

                    if (str_contains($t_image,'wikipedia')) {
                    
                        DiseaseImage::updateOrCreate([
                            'origin_url'=>$t_image
                        ],[
                            'scientific_name'=>$type->scientific_name,
                            'origin_url'=>$t_image,
                            'folder'=>$folder_name,
                            'name'=>$name,
                            'license' => 45,
                            'license_name' =>'Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)',
                            'license_url'=>'https://creativecommons.org/licenses/by-sa/3.0/deed.en'
                        ]);

                    }

                    if (str_contains($t_image,'pexel')) {
                    
                        DiseaseImage::updateOrCreate([
                            'origin_url'=>$t_image
                        ],[
                            'scientific_name'=>$type->scientific_name,
                            'origin_url'=>$t_image,
                            'folder'=>$folder_name,
                            'name'=>$name,
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }


                    if (str_contains($t_image,'pxhere')) {
                    
                        DiseaseImage::updateOrCreate([
                            'origin_url'=>$t_image
                        ],[
                            'scientific_name'=>$type->scientific_name,
                            'origin_url'=>$t_image,
                            'folder'=>$folder_name,
                            'name'=>$name,
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }


                    if (str_contains($t_image,'plantnet')) {
                    
                        DiseaseImage::updateOrCreate([
                            'origin_url'=>$t_image
                        ],[
                            'scientific_name'=>$type->scientific_name,
                            'origin_url'=>$t_image,
                            'folder'=>$folder_name,
                            'name'=>$name,
                            'license' => 44,
                            'license_name' =>'Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)',
                            'license_url'=>'https://creativecommons.org/licenses/by-sa/4.0/'
                        ]);

                    }


                    if (str_contains($t_image,'rawpixel')) {
                    
                        DiseaseImage::updateOrCreate([
                            'origin_url'=>$t_image
                        ],[
                            'scientific_name'=>$type->scientific_name,
                            'origin_url'=>$t_image,
                            'folder'=>$folder_name,
                            'name'=>$name,
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }


                    if (str_contains($t_image,'pixabay')) {
                    
                        DiseaseImage::updateOrCreate([
                            'origin_url'=>$t_image
                        ],[
                            'scientific_name'=>$type->scientific_name,
                            'origin_url'=>$t_image,
                            'folder'=>$folder_name,
                            'name'=>$name,
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }

                    if (str_contains($t_image,'pxfuel')) {
                    
                        DiseaseImage::updateOrCreate([
                            'origin_url'=>$t_image
                        ],[
                            'scientific_name'=>$type->scientific_name,
                            'origin_url'=>$t_image,
                            'folder'=>$folder_name,
                            'name'=>$name,
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }


                    if (str_contains($t_image,'paperflare')) {
                    
                        DiseaseImage::updateOrCreate([
                            'origin_url'=>$t_image
                        ],[
                            'scientific_name'=>$type->scientific_name,
                            'origin_url'=>$t_image,
                            'folder'=>$folder_name,
                            'name'=>$name,
                            'license' => 451,
                            'license_name' =>'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication',
                            'license_url'=>'https://creativecommons.org/publicdomain/zero/1.0/'
                        ]);

                    }



                }



            }
        
            // if ($k == 10) {break;}

        }





    }
}