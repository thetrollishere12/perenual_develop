<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\SpeciesCareGuide;
use App\Models\Species;

class GuideAnalyzer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GuideAnalyzer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze guides and see whats up';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $careguide = SpeciesCareGuide::all();

        foreach ($careguide as $key => $guide) {
            
            $array = [];

            foreach ($guide->section(null)->get() as $value => $section) {

                if ($section->type == 'watering') {
                
                    // if(preg_match_all('/\d+\s*days(?!.*week)|week/i', $section->description, $matches)){
        
                    // }


                    // if(preg_match_all('/(summer|winter|spring|fall|January|February|March|April|May|June|July|August|September|October|November|December)|(month|warm|cold month)/i', $section->description, $matches)){

                    // }


                    if(preg_match_all('/\bmorning|morningtime|evening|afternoon|night|midnight|midday|dawn|sunrise|dusk|sunset|noon|noontime\b/i', $section->description, $matches)){
                        $array['watering_period'] = $matches[0][0]; 

                    }


                    if(preg_match_all('/\b(\d+)\s*(gallons|gallon|ml)\b/i', $section->description, $matches)){

                        $value = $matches[1][0];
                        $unit = $matches[2][0];

                        $array['volume_water_requirement'] = [
                            'unit'=>$unit,
                            'value'=>$value
                        ];

                    }


                    if(preg_match_all('/\b(\d+)\s*(inches|inch|cm|mm|meters|meter|feet|foot)|an\s*inch\b/i', $section->description, $matches)){

                        $value = $matches[1][0];
                        $unit = $matches[2][0];

                        $array['depth_water_requirement'] = [
                            'unit'=>$unit,
                            'value'=>$value
                        ];
                        
                    }






                }elseif($section->type  == 'sunlight'){

                    // if(preg_match_all('/\b(sunlight|sun|light|shade)\b/i', $section->description, $matches)){

                    // }

                    if(preg_match_all('/\b(\d+)(?:\s*(?:-|to)\s*(\d+))?(?:\sor more|\sor less)?\s*hours\b/i', $section->description, $matches)){
                        $min = $matches[1][0]; // First group captures the minimum value

                        // Check if maximum value exists, if not, set it as the minimum value
                        $max = isset($matches[2][0]) ? $matches[2][0] : $matches[1][0];

                        $array['sunlight_duration'] = [
                            'min'=>$min,
                            'max'=>$max,
                            'unit'=>'hours'
                        ];

                    }elseif(preg_match_all('/\b((?:a couple|a few)(?: more)?|\d+)(?:\s*(?:-|to)\s*(\d+))?(?:\sor more|\sor less)?\s*hours\b/i', $section->description, $matches)){

                        $array['sunlight_duration'] = [
                            'min'=>2,
                            'max'=>5,
                            'unit'=>'hours'
                        ];

                    }


                    if(preg_match_all('/\bmorning|morningtime|evening|afternoon|night|midnight|midday|dawn|sunrise|dusk|sunset|noon|noontime\b/i', $section->description, $matches)){

                        if (count($matches[0]) == 1) {
                       
                            $array['sunlight_period'] = $matches[0][0];

                        }

                    }


                }elseif ($section->type == 'pruning') {

                    $phrases = array(
                        'early spring' => array('March', 'April'),
                        'spring' => array('March', 'April', 'May'),
                        'mid spring' => array('April'),
                        'late spring' => array('May'),
                        'early summer' => array('June', 'July'),
                        'summer' => array('June', 'July', 'August'),
                        'mid summer' => array('July'),
                        'late summer' => array('August'),
                        'early autumn' => array('September', 'October'),
                        'autumn' => array('September', 'October', 'November'),
                        'mid autumn' => array('October'),
                        'late autumn' => array('November'),
                        'early winter' => array('December', 'January'),
                        'winter' => array('December', 'January', 'February'),
                        'mid winter' => array('January'),
                        'late winter' => array('February'),
                        'growing season' => array('March', 'April', 'May', 'June', 'July', 'August', 'September'), // typical growing season for most plants
                        // Add the individual months here, mapping each one to an array containing just itself.
                        'january' => array('January'),
                        'february' => array('February'),
                        'march' => array('March'),
                        'april' => array('April'),
                        'may' => array('May'),
                        'june' => array('June'),
                        'july' => array('July'),
                        'august' => array('August'),
                        'september' => array('September'),
                        'october' => array('October'),
                        'november' => array('November'),
                        'december' => array('December')
                    );

                    // Prepare the regular expression pattern.
                    $pattern = "/\b(" . implode("|", array_keys($phrases)) . ")\b/i";
                    if (preg_match_all($pattern, $section->description, $matches)) {
                        $foundMonths = array();
                        foreach ($matches[0] as $match) {
                            $foundMonths = array_merge($foundMonths, $phrases[strtolower($match)]);
                        }
     
                        $array['pruning_month']  = $foundMonths;
                    }




                    // if(preg_match_all('/\b(\d+(?:\/\d+)|\d+(?:\.\d+)?%)\b/i', $section->description, $matches) || preg_match_all('/(\d+(?:\/\d+)?(?:\.\d+)?%|\d+(?:\.\d+)?%)/i', $section->description, $matches) || preg_match_all('/\b(\d+(?:\/\d+)?(?:-third))\b/i', $section->description, $matches)){

                    //     if (count($matches[0]) == 1) {
                    //         $array['pruning_takeoff_amount'] = $matches[0][0];
                    //     }else{
                          
                    //     }

                        

                    // }




                    // Yearly Pruning count
                    if(preg_match('/\b(\d+\s*(?:time|times)|once|annually|twice|every\s*\d+\s*(?:day|week|month))\b/i', $section->description, $matches)){

                        if ($matches[0] == "once" || $matches[0] == "1 time" || $matches[0] == "annually") {
                            
                            $array['pruning_count'] = [
                                'amount'=>1,
                                'interval'=>'yearly'
                            ];

                        }

                        if ($matches[0] == "twice" || $matches[0] == "2 times") {

                            $array['pruning_count'] = [
                                'amount'=>2,
                                'interval'=>'yearly'
                            ];
                        }

                        if ($matches[0] == "3 times") {
                            
                            $array['pruning_count'] = [
                                'amount'=>3,
                                'interval'=>'yearly'
                            ];

                        }

                    }

                    
                }

            }

            // dd($array);
            Species::where('id',$guide->species_id)->update($array);


        }

    }
}
