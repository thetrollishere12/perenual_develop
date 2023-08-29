<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\SpeciesUnauthorized;
use App\Models\Species;

class SpeciesXtra extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SpeciesXtra';

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

        ini_set('memory_limit', '1024M');

        $species = SpeciesUnauthorized::where('source','Truffle')->get();

        foreach ($species as $key => $specie) {

            $found = Species::where('scientific_name','LIKE','%'.$specie->scientific_name.'%')->get();

            if ($found->count()>0) {
                echo $specie->scientific_name;
                dd($found->first()->scientific_name);
                // dd($specie->attributes);
            }

        }

        // $species = Species::all();

        // foreach ($species as $key => $specie) {
            
        //     if ($specie->watering_general_benchmark) {
                

        //         preg_match_all('/(\d+-\d+|\d+\s*days)/', $specie->watering_general_benchmark, $matches);

        //         // Checking if matches found
        //         if (!empty($matches[0])) {
        //             foreach ($matches[0] as $match) {
        //                 // Print the number of days
        //                 Species::where('id',$specie->id)->update([
        //                     'watering_general_benchmark'=>$match
        //                 ]);

        //             }
        //         }



        //     }

        // }


    }
}
