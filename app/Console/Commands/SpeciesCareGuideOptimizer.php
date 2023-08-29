<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SpeciesCareGuide;
use App\Models\SpeciesCareGuideSection; 
use App\Models\Species; 

class SpeciesCareGuideOptimizer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SpeciesCareGuideOptimizer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize Guides';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(0);

        // $guides = SpeciesCareGuide::all();

        // foreach ($guides as $value) {

        //     $section = SpeciesCareGuideSection::where('guide_id',$value->id)->get();
            
        //     if ($section->count() > 3) {
        //         SpeciesCareGuideSection::where('guide_id',$value->id)->where('generated_user_id',0)->delete();
        //         echo "deleted for guide - ".$value->id;
        //     }

        // }


















        // $type = 'sunlight';
        // $sections = SpeciesCareGuideSection::where('type',$type)->get();

        // foreach ($sections as $key => $section) {
            
        //     if(preg_match_all('/\b(\d+)(?:\s*(?:-|to)\s*(\d+))?(?:\sor more|\sor less)?\s*hours\b/i', $section->description, $matches)){


        //     }elseif(preg_match_all('/\b((?:a couple|a few)(?: more)?|\d+)(?:\s*(?:-|to)\s*(\d+))?(?:\sor more|\sor less)?\s*hours\b/i', $section->description, $matches)){


        //     }else{

        //         $guide = SpeciesCareGuide::where('id',$section->guide_id)->first();

        //         $species = Species::where('id',$guide->species_id)->first();

        //         SpeciesCareGuideSection::where('id',$section->id)->update([
        //             'description'=>ltrim(AiGenerateTextV2('Write on how much & when for '.$type.' for a plant species called'.$species->common_name." (".$species->scientific_name[0].') in detail but dont make it long.',[],0)),
        //             'generated_user_id'=>0
        //         ]);

        //         echo "Redo for section ID - ".$section->id;

        //     }

        // }



        // Watering




        // $type = 'watering';
        // $sections = SpeciesCareGuideSection::where('type',$type)->get();

        // foreach ($sections as $key => $section) {
            
            

        //     $phrases = array(
        //         "once a week" => "5-7",
        //         "twice a week" => "3-4",
        //         "three times a week" => "2-3",
        //         "four times a week" => "1-2",
        //         "biweekly" => "10-14",
        //         "a week and a half" => "7-12",
        //         "every 3 weeks" => "17-21",
        //         "1 time a week" => "5-7",
        //         "2 times a week" => "3-4"
        //     );

        //     foreach ($phrases as $phrase => $replacement) {
        //         if (strpos($section->description, $phrase) !== false) {
        //             echo "Found phrase: '$phrase', which is equivalent to: $replacement\n";
        //         }
        //     }



        // }


        
    }
}