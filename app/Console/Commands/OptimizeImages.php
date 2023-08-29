<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Storage;

use App\Models\SpeciesImage;
use App\Models\ArticleFaqImage;
use App\Models\DiseaseImage;


use ImageOptimizer;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:OptimizeImages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'OptimizeImage All Images';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Species Image
        $images = SpeciesImage::skip(11000)->take(1000)->get();

        $array = ['og','regular','medium','small','thumbnail'];

        foreach ($images as $key => $image) {
                
            $filePath = Storage::disk('public')->path('species_image/'.$image->folder.'/og/'.$image->name);

            ImageOptimizer::optimize($filePath);

            $filePath = Storage::disk('public')->path('species_image/'.$image->folder.'/regular/'.$image->name);

            ImageOptimizer::optimize($filePath);

            $filePath = Storage::disk('public')->path('species_image/'.$image->folder.'/medium/'.$image->name);

            ImageOptimizer::optimize($filePath);

            $filePath = Storage::disk('public')->path('species_image/'.$image->folder.'/small/'.$image->name);

            ImageOptimizer::optimize($filePath);

            $filePath = Storage::disk('public')->path('species_image/'.$image->folder.'/thumbnail/'.$image->name);

            ImageOptimizer::optimize($filePath);

        }

        return 'Done';

        // Faq Image
        $images = ArticleFaqImage::all();

        $array = ['og','regular','medium'];

        foreach ($images as $key => $image) {
            
            try{

            foreach ($array as $name) {
                
                $filePath = Storage::disk('public')->path('article_faq/'.$image->folder.'/'.$name.'.jpg');

                ImageOptimizer::optimize($filePath);

            }


            }catch(\Exception $e){

                return $image->id;
                continue;

            }


        }

        return 'Done';







        // Species Image
        $images = DiseaseImage::skip(0)->take(100)->get();

        $array = ['og','regular','medium','small','thumbnail'];

        foreach ($images as $key => $image) {
                
            $filePath = Storage::disk('public')->path('species_disease/'.$image->folder.'/og/'.$image->name);

            ImageOptimizer::optimize($filePath);

            $filePath = Storage::disk('public')->path('species_disease/'.$image->folder.'/regular/'.$image->name);

            ImageOptimizer::optimize($filePath);

            $filePath = Storage::disk('public')->path('species_disease/'.$image->folder.'/medium/'.$image->name);

            ImageOptimizer::optimize($filePath);

            $filePath = Storage::disk('public')->path('species_disease/'.$image->folder.'/small/'.$image->name);

            ImageOptimizer::optimize($filePath);

            $filePath = Storage::disk('public')->path('species_disease/'.$image->folder.'/thumbnail/'.$image->name);

            ImageOptimizer::optimize($filePath);

        }

        return 'Done';





    }
}