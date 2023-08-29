<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SpeciesImage;
use App\Models\Species;
use Storage;

class DeleteSpeciesImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DeleteSpeciesImage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete images and clear from database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        

        set_time_limit(0);

        // $species = SpeciesImage::where('name','LIKE','%'.'%'.'%')->get();

        // foreach ($species as $key => $specie) {
        //     SpeciesImage::where('id',$specie->id)->update([
        //         'name'=>str_replace('%','',$specie->name)
        //     ]);
        // }
        
        // $species = Species::where('default_image','LIKE','%'.'%'.'%')->get();

        // foreach ($species as $key => $specie) {

        //     Storage::disk('public')->move("species_image/".$specie->folder.'/og/'.$specie->default_image,"species_image/".$specie->folder.'/og/'.str_replace('%','',$specie->default_image));

        //     Storage::disk('public')->move("species_image/".$specie->folder.'/medium/'.$specie->default_image,"species_image/".$specie->folder.'/medium/'.str_replace('%','',$specie->default_image));

        //     Storage::disk('public')->move("species_image/".$specie->folder.'/regular/'.$specie->default_image,"species_image/".$specie->folder.'/regular/'.str_replace('%','',$specie->default_image));

        //     Storage::disk('public')->move("species_image/".$specie->folder.'/small/'.$specie->default_image,"species_image/".$specie->folder.'/small/'.str_replace('%','',$specie->default_image));

        //     Storage::disk('public')->move("species_image/".$specie->folder.'/thumbnail/'.$specie->default_image,"species_image/".$specie->folder.'/thumbnail/'.str_replace('%','',$specie->default_image));

        //     Species::where('id',$specie->id)->update([
        //         'default_image'=>str_replace('%','',$specie->default_image)
        //     ]);

        // }

        // Species::where('default_image',null)->orWhere('folder',null)->update([
        //     'default_image'=>null,
        //     'folder'=>null
        // ]);

        // $species = Species::where('default_image','!=',null)->where('folder','!=',null)->get();

        // foreach ($species as $key => $specie) {
            
        //     if (!Storage::disk("public")->exists("species_image/".$specie->folder."/og/".$specie->default_image)) {

                
        //         Storage::disk('public')->deleteDirectory("species_image/".$specie->folder);

        //         Species::where('id',$specie->id)->update([
        //             'default_image'=>null,
        //             'folder'=>null
        //         ]);



        //     }

        // }


        $fp = fopen(Storage::disk('local')->path("textFile/image_error_delete.csv"),'a');

        $db = SpeciesImage::whereIn('license',[1,2,3])->get();

        foreach ($db as $speciesImg) {

            $species = Species::where('image','LIKE','%'.pathinfo($speciesImg->name)['filename'].'%')->first();

            $array = $species['image'];

            foreach ($species['image'] as $key => $image) {

                if (str_contains($image,pathinfo($speciesImg->name)['filename'])) {

                    unset($array[$key]);

                    Species::where('id',$species->id)->update([
                        'image'=>$array
                    ]);

                    Storage::disk('public')->delete('species_image/'.$speciesImg->folder.'/og/'.pathinfo($speciesImg->name)['basename']);
                    Storage::disk('public')->delete('species_image/'.$speciesImg->folder.'/regular/'.pathinfo($speciesImg->name)['basename']);
                    Storage::disk('public')->delete('species_image/'.$speciesImg->folder.'/medium/'.pathinfo($speciesImg->name)['basename']);
                    Storage::disk('public')->delete('species_image/'.$speciesImg->folder.'/small/'.pathinfo($speciesImg->name)['basename']);
                    Storage::disk('public')->delete('species_image/'.$speciesImg->folder.'/thumbnail/'.pathinfo($speciesImg->name)['basename']);

                    SpeciesImage::find($speciesImg->id)->delete();

                }
            }


            Species::where('default_image','LIKE','%'.pathinfo($speciesImg->name)['filename'].'%')->update([
                'default_image'=>null
            ]);

            
        }




    }
}