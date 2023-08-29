<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Species;
use App\Models\SpeciesCareGuide;
use App\Models\SpeciesImage;

use App\Models\Comment\SpeciesComment;
use App\Models\Comment\SpeciesCommentReview;

class LinkSpeciesIdForColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:LinkSpeciesIdToColumn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Linked specific names with ID';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        // $queries = SpeciesCareGuide::all();

        // foreach ($queries as $key => $query) {
            
        //     try{

        //     $species = Species::where('scientific_name','LIKE','%'.$query->scientific_name[0].'%')->where('common_name',$query->common_name)->first();

        //     SpeciesCareGuide::where('id',$query->id)->update([
        //         'species_id'=>$species->id
        //     ]);

        //     }catch(\Exception $e){
        //         continue;
        //     }
            
        // }

        $queries = SpeciesImage::whereBetween('id',[10704,12486])->get();

        foreach ($queries as $key => $query) {
            
            try{

                // By Scientific Name
                // $species = Species::where('scientific_name','LIKE','%'.$query->scientific_name[0].'%')->first();

                // SpeciesImage::where('id',$query->id)->update([
                //     'species_id'=>$species->id
                // ]);



                // By Folder
                // preg_match('/^\d+/', $query->folder, $matches);

                // $species = Species::where('id',$matchese[0])->first();

                // SpeciesImage::where('id',$query->id)->update([
                //     'species_id'=>$species->id
                // ]);


                // Get rid of names with semicolon
                if (stripos($query->description,$query->species()->first()->common_name) !== false) {
                    if (strpos($query->description, ';') !== false) {
           
                    $pattern = '/^.*?;\s*/s';

                    $cleanedString = preg_replace($pattern, '', $query->description);

                    SpeciesImage::where('id',$query->id)->update([
                        'description'=>$cleanedString
                    ]);
                    }
                }




            }catch(\Exception $e){
                dd($e);
                continue;
            }

        }



        // $queries = SpeciesComment::all();

        // foreach ($queries as $key => $query) {
            
        //     try{

        //     $species = Species::where('scientific_name','LIKE','%'.$query->scientific_name[0].'%')->first();

        //     SpeciesComment::where('id',$query->id)->update([
        //         'species_id'=>$species->id
        //     ]);

        //     }catch(\Exception $e){
        //         continue;
        //     }

        // }




        // $queries = SpeciesCommentReview::all();

        // foreach ($queries as $key => $query) {
            
        //     try{

        //     $species = Species::where('scientific_name','LIKE','%'.$query->scientific_name[0].'%')->first();

        //     SpeciesCommentReview::where('id',$query->id)->update([
        //         'species_id'=>$species->id
        //     ]);

        //     }catch(\Exception $e){
        //         continue;
        //     }

        // }




    }
}