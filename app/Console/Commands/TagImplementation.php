<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Species;

class TagImplementation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AddTagToSpecies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding tags to Species';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $tag = "apple";

        $species = Species::where('tags',null)->update([
            'tags'=>'[]'
        ]);

        $species = Species::where('common_name','LIKE','%apple %')->get();


        foreach ($species as $key => $species) {
            
            $h = $species->tags;

            if(!in_array($tag, $h)){

                array_push($h,$tag);

            }   
  
            Species::where('id',$species->id)->update([
                'tags'=>$h
            ]);

        }


    }
}
