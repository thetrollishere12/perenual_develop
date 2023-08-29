<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\SpeciesImage;
use App\Models\Species;

class SpeciesImageAnatomy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SpeciesImageAnatomy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Image Anatomy';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        

        // Fetch all species
        $species = Species::all();

        // Loop through each species
        foreach ($species as $specie) {
            
            // Fetch all images of this species
            $images = SpeciesImage::where('species_id', $specie->id)->get();

            // Initialize the plant_anatomy array
            $plant_anatomy = [];

            // Loop through each image
            foreach ($images as $image) {
                // Parse the JSON data
                $anatomy = $image->plant_image_anatomy;

                // Check if the anatomy array is not empty
                if (!empty($anatomy)) {
                    // Add the anatomy data to the plant_anatomy array
                    $plant_anatomy = array_merge($plant_anatomy, $anatomy);
                }
            }


            // Update the species with the aggregated anatomy data
            // We are using the raw update with json_encode function to ensure compatibility with the JSON column type
            Species::where('id', $specie->id)->update([
                'plant_anatomy' => $plant_anatomy,
            ]);

            echo "Done - ".$specie->id;

        }






        $species = Species::all();

        foreach ($species as $specie) {
            // Parse the JSON data
            $anatomy = $specie->plant_anatomy;
       
            // Initialize a new structure
            $unique_anatomy = [];

            // Loop over the anatomy data
            foreach ($anatomy as $part) {
                // Make sure 'part' and 'color' keys exist in the array
                if (isset($part['part']) && isset($part['color'])) {
                    // Check if this part is already in the unique_anatomy array
                    $exists = array_search($part['part'], array_column($unique_anatomy, 'part'));

                    if ($exists !== false) {
                        // If the part exists, add the color if it's not already there
                        if (!in_array($part['color'], $unique_anatomy[$exists]['color'])) {
                            $unique_anatomy[$exists]['color'][] = $part['color'];
                        }
                    } else {
                        // If the part doesn't exist, add it with the color to the unique_anatomy array
                        $unique_anatomy[] = ['part' => $part['part'], 'color' => [$part['color']]];
                    }
                }
            }

            // Now, $unique_anatomy is an associative array where each key is a part, and each value is an array of unique colors for that part.
            // If you want to store this information back in the database, you can update the species record

            Species::where('id', $specie->id)->update([
                'plant_anatomy' => $unique_anatomy,
            ]);

            echo "Done Revising - ".$specie->id;

        }





    }
}
