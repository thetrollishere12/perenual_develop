<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SpeciesDetailArrayRevise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SpeciesDetailArrayRevise';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace Details and array';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {



        // $species = Species::all();

        // foreach ($species as $key => $value) {

        //     Species::where('id',$value->id)->update([
        //         'propagation'=>array_map('ucwords',$value->propagation)
        //     ]);

        // }



        // Removing value in array


        // $words = ["Cloves","Bulbs","Sod","Spikelets","Vegetative Spread"];

        // foreach ($words as $word) {
            
        //     $species = Species::where('propagation','LIKE','%'.$word.'%')->get();

        //     foreach ($species as $key => $value) {



        //         if (in_array($word,$value->propagation)) {
                    
        //             $array = $value->propagation;

        //             unset($array[array_search($word,$array)]);

        //             Species::where('id',$value->id)->update([
        //                 'propagation'=>$array
        //             ]);

        //         }

        //     }

        // }


        // Replacing value in array

        $words = ["Fertilization"];

        foreach ($words as $word) {
            
            $replace = "Pollination";

            $species = Species::where('propagation','LIKE','%'.$word.'%')->get();

            foreach ($species as $key => $value) {

                if (in_array($word,$value->propagation)) {
                    
                    Species::where('id',$value->id)->update([
                        'propagation'=>str_replace($word,$replace,$value->propagation)
                    ]);

                }

            }

        }



    }
}
