<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Species;
use Storage;



class SpeciesDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SpeciesDetails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get details for species from AI';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        

        $species = Species::whereBetween('id',[1,10104])->where('fruits',1)->get();

        set_time_limit(0);

        $fp = fopen(Storage::disk('local')->path("textFile/seeds.csv"),'a');

        // $columns = array('id','type');

        // fputcsv($fp,$columns);

        foreach ($species as $value) {

            $text = "";

            $array = [];



            // try{
                    
            //     $answer = AiGenerateText('If not certified just say skip but in general how often to water in days for a plant species called'.$value->common_name." (".$value->scientific_name[0].') during the summer in loam soil?',['temperature'=>0]);

            //     if (strpos(strtolower($answer), 'skip') !== false) {
            //         continue;
            //     }

            //     $array['watering_general_benchmark'] = trim($answer);

            // }catch(\Exception $e){

            //     $columns = array($value->id,'watering_general_benchmark');
            //     fputcsv($fp,$columns);

            // }



            // try{

            //     $answer = AiGenerateText('Just the country in bullet point list, where is the origin of the '.$value->scientific_name[0].' also known as '.$value->common_name.'?',['temperature'=>0]);

            //     $array['origin'] = array_map('trim',array_filter(explode("• ",str_replace("\n","",$answer))));


            // }catch(\Exception $e){

            //     $columns = array($value->id,'origin');
            //     fputcsv($fp,$columns);

            // }



            // try{

            //     $answer = AiGenerateText('From zone to zone what is the global hardiness zone for '.$value->scientific_name[0].' also known as '.$value->common_name.' be in numbers only?',['temperature'=>0]);

            //     if (!str_contains($answer,'-')) {
            //         continue;
            //     }

            //     $t = explode(" ",$answer);

            //     foreach ($t as $key => $tt) {
                    
            //         if (preg_match('~[0-9]+~', $tt)) {
                        
            //             if (str_contains($answer,'-')) {
            //                 $array['hardiness']['min'] = str_replace('.','',explode("-",trim($tt))[0]);
            //                 $array['hardiness']['max'] = str_replace('.','',explode("-",trim($tt))[1]);
            //             }else{
   
            //                 // $array['hardiness']['min'] = trim($tt);
            //                 // $array['hardiness']['max'] = trim($tt);
            //             }

            //         }

            //     }

            //     if (str_contains($answer,'USDA')) {
            //         $array['hardiness']['zone'] = 'USDA';
            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'hardiness');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->type) {

            //         $answer = AiGenerateText('Picking between Wildflower, Thistle, Flowers, Weed, Fern, Reeds, Bamboo, Moss, Grass, Tree, Bush, Shrub, Cattails, Herbs, Cactus, Fruit, Vegetable, Climbers and Creepers as choices, what plant type would '.$value->scientific_name[0].' also known as '.$value->common_name.' be in 1 word without period?',['temperature'=>0]);

            //         $array['type'] = ucfirst(trim($answer));

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'type');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->propagation) {

            //         $answer = AiGenerateText('Just the methods, what are ways to propogate for '.$value->scientific_name[0].' also known as '.$value->common_name.' in bullet points without description',['temperature'=>0]);

            //         $array['propagation'] = ucfirst(trim(str_replace(["-","-"]," ",$answer)));
            //         $array['propagation'] = array_map('trim',explode(" \n",$array['propagation']));

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'propagation');
            //     fputcsv($fp,$columns);

            // }

            // try{
                    
            //     $answer = AiGenerateText('Write a description up to 110 words for a plant species called'.$value->common_name." (".$value->scientific_name[0].').',['temperature'=>1]);

            //     $array['description'] = trim($answer);

            // }catch(\Exception $e){

            //     $columns = array($value->id,'description');
            //     fputcsv($fp,$columns);

            // }

            // try{
                
            //     if (!$value->sunlight) {
                    
            //         $answer = AiGenerateText('between full shade, part shade, sun-part shade and full sun, how much sunlight does '.$value->scientific_name[0].' also known as '.$value->common_name.' need?',['temperature'=>0]);
           
            //         $keyword = array("full sun","part shade","part-shade","full-sun","full shade","full-shade","sun part shade","sun-part shade","sun-part-shade");

            //         $color = [];

            //         foreach ($keyword as $token) {
            //             if (stristr($answer, $token) !== FALSE) {
            //                 $color[] = $token;
            //             }
            //         }

            //         $array['sunlight'] = $color;

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'sunlight');
            //     fputcsv($fp,$columns);

            // }

            // if (!$value->family) {
            //     $answer = AiGenerateText('What is the family name for the species '.$value->scientific_name[0].' need in 1 word without period?');

            //     $array['family'] = ucfirst(trim($answer));
            // }

            // try{

            //     if (!$value->watering) {

            //         $answer = AiGenerateText('Picking between Average, Frequent and Minimal as choices, how much watering does '.$value->scientific_name[0].' also known as '.$value->common_name.' need in 1 word without period?',['temperature'=>0]);

            //         $array['watering'] = ucfirst(trim($answer));

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'watering');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->cycle) {
            //         $answer = AiGenerateText('Picking between biennial, biannual, perennial and annual as choices, what is the plant cycle for the plant species '.$value->scientific_name[0].' also known as '.$value->common_name.'?',['temperature'=>0]);

            //         $array['cycle'] = ucfirst(trim($answer));
            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'cycle');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->growth_rate) {
            //         $answer = AiGenerateText('Picking between High, Moderate and Low as choices, what is the growth rate for '.$value->scientific_name[0].' also known as '.$value->common_name.' in 1 word without period?',['temperature'=>0]);

            //         $array['growth_rate'] = ucfirst(trim($answer));
            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'growth_rate');
            //     fputcsv($fp,$columns);

            // }


            // try{

            //     if (!$value->maintenance) {
            //         $answer = AiGenerateText('Picking between High, Moderate, Low and None as choices, how much maintenance does '.$value->scientific_name[0].' also known as '.$value->common_name.' need in 1 word without period?',['temperature'=>0]);

            //         $array['maintenance'] = ucfirst(trim($answer));
            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'maintenance');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->care_level) {
            //         $answer = AiGenerateText('Picking between Easy, Medium, Hard and Extreme as choices, what level of care does '.$value->scientific_name[0].' also known as '.$value->common_name.' does it need in 1 word without period?',['temperature'=>0]);

            //         $array['care_level'] = ucfirst(trim($answer));
            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'care_level');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->poisonous_to_humans) {
            //         $answer = AiGenerateText('Just yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' poisonous to humans?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['poisonous_to_humans'] = 1;
            //         }
            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'poisonous_to_humans');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->poisonous_to_pets) {
            //         $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' poisonous to pets in 1 word?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['poisonous_to_pets'] = 1;
            //         }
            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'poisonous_to_pets');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     $answer = AiGenerateText('Just yes or no, does '.$value->scientific_name[0].' also known as '.$value->common_name.' have cones?',['temperature'=>0]);

            //     if (str_contains(strtolower(trim($answer)),'yes')) {
            //         $array['cones'] = 1;
            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'cones');
            //     fputcsv($fp,$columns);

            // }

            

            // try{
            
            //     if ($value->edible_leaf) {
                
            //         if (!$value->leaf_flavor_profile) {
                        
            //             $answer = AiGenerateText('Just between floral,nutty,earthy,salty,sweet, sour, tart, bitter, spicy as choices, what is the leaf flavor profile for '.$value->scientific_name[0].' also known as '.$value->common_name.'?',['temperature'=>0]);
            //             $keyword = array("floral","nutty","earthy","salty","sweet","sour","tart","bitter","spicy");

            //             $profile = [];

            //             foreach ($keyword as $token) {
            //                 if (stristr($answer, $token) !== FALSE) {
            //                     $profile[] = $token;
            //                 }
            //             }

            //             $array['leaf_flavor_description'] = $answer;
            //             $array['leaf_flavor_profile'] = $profile;

            //         }
                
            //     }
            
            // }catch(\Exception $e){

            //     $columns = array($value->id,'leaf_flavor_profile');
            //     fputcsv($fp,$columns);

            // }

            // if ($value->flowers) {
            
            //     try{

            //         if (!$value->edible_flower) {
                        
            //             $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' flowers edible for humans in 1 word?',['temperature'=>0]);

            //             if (str_contains(strtolower(trim($answer)),'yes')) {
            //                 $array['edible_flower'] = 1;
                            
            //                 try{
                            
            //                     $answer = AiGenerateText('Just between floral,nutty,earthy,salty,sweet, sour, tart, bitter, spicy as choices, what is the flower taste profile for '.$value->scientific_name[0].' also known as '.$value->common_name.'?',['temperature'=>0]);
            //                     $keyword = array("floral","nutty","earthy","salty","sweet","sour","tart","bitter","spicy");
                    
            //                     $profile = [];
                    
            //                     foreach ($keyword as $token) {
            //                         if (stristr($answer, $token) !== FALSE) {
            //                             $profile[] = $token;
            //                         }
            //                     }
                    
            //                     $array['flower_flavor_description'] = $answer;
            //                     $array['flower_flavor_profile'] = $profile;
                            
            //                 }catch(\Exception $e){
                            
            //                     $columns = array($value->id,'flower_flavor_profile');
            //                     fputcsv($fp,$columns);
                            
            //                 }
                            
            //             }

            //         }

            //     }catch(\Exception $e){

            //         $columns = array($value->id,'edible_flower');
            //         fputcsv($fp,$columns);

            //     }
            
            // }
            







            // try{
            
            //     if ($value->fruits) {
                
            //         if (!$value->fruiting_month) {
                        
            //             $answer = AiGenerateText('Just between January, February, March, April, May, June, July, August, September, October, November, December as choices, when do '.$value->scientific_name[0].' also known as '.$value->common_name.' start fruiting?',['temperature'=>0]);
            //             $keyword = array("january","february","march","april","may","june","july","august","September","october","november","december");

            //             $profile = [];

            //             foreach ($keyword as $token) {
            //                 if (stristr($answer, $token) !== FALSE) {
            //                     $profile[] = $token;
            //                 }
            //             }

            //             $array['fruiting_month_description'] = $answer;
            //             $array['fruiting_month'] = $profile;

            //         }
                
            //     }
            
            // }catch(\Exception $e){

            //     $columns = array($value->id,'fruiting_month');
            //     fputcsv($fp,$columns);

            // }


            // try{
            
            //     if ($value->edible_fruit) {
                
            //         if (!$value->fruit_flavor_profile) {
                        
            //             $answer = AiGenerateText('Just between floral,nutty,earthy,salty,sweet, sour, tart, bitter, spicy as choices, what is the flavor profile for '.$value->scientific_name[0].' also known as '.$value->common_name.'?',['temperature'=>0]);
            //             $keyword = array("floral","nutty","earthy","salty","sweet","sour","tart","bitter","spicy");

            //             $profile = [];

            //             foreach ($keyword as $token) {
            //                 if (stristr($answer, $token) !== FALSE) {
            //                     $profile[] = $token;
            //                 }
            //             }

            //             $array['fruit_flavor_description'] = $answer;
            //             $array['fruit_flavor_profile'] = $profile;

            //         }
                
            //     }
            
            // }catch(\Exception $e){

            //     $columns = array($value->id,'fruit_flavor_profile');
            //     fputcsv($fp,$columns);

            // }

                
            // try{
            
            //     if ($value->fruits) {
                
            //         if (!$value->fruit_color) {
                        
            //             $answer = AiGenerateText('only a list of colors, what colors are the fruits of '.$value->scientific_name[0],['temperature'=>0]);
            //             $keyword = array("silver","gold","bronze","blue","green","red","yellow","purple","orange","teal","olive","azure","brown","gray","pink","maroon","violet","magenta","cream","tan","coral","burgundy","mauve","peach","indigo","ruby","cyan","black","white","gray");

            //             $color = [];

            //             foreach ($keyword as $token) {
            //                 if (stristr($answer, $token) !== FALSE) {
            //                     $color[] = $token;
            //                 }
            //             }

            //             $array['fruit_color'] = $color;

            //         }
                
            //     }
            
            // }catch(\Exception $e){

            //     $columns = array($value->id,'fruit_color');
            //     fputcsv($fp,$columns);

            // }
            

            // try{

            //     if (!$value->fruits) {
                    
            //         $answer = AiGenerateText('Just yes or no, does '.$value->scientific_name[0].' also known as '.$value->common_name.' have fruits?',['temperature'=>0]);

            //         if (str_contains(strtolower(trim($answer)),'yes')) {
            //             $array['fruits'] = 1;

                        // try{

                        //     if (!$value->edible_fruit) {
                                
                        //         $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' fruits edible for humans in 1 word?',['temperature'=>0]);

                        //         if (str_contains(strtolower(trim($answer)),'yes')) {
                        //             $array['edible'] = 1;
                        //         }

                        //     }

                        // }catch(\Exception $e){

                        //     $columns = array($value->id,'edible');
                        //     fputcsv($fp,$columns);

                        // }

                        // try{

                        //     if (!$value->harvest_season) {
                                
                        //         $answer = AiGenerateText('Picking Between Fall, Winter, Spring and Summer as choices, what season are '.$value->scientific_name[0].' fruits ready to be harvest in 1 word without period?',['temperature'=>0]);

                        //         if (str_contains('yes',strtolower($answer))) {
                        //             $array['harvest_season'] = trim($answer);
                        //         }

                        //     }

                        // }catch(\Exception $e){

                        //     $columns = array($value->id,'harvest_season');
                        //     fputcsv($fp,$columns);

                        // }

                        // try{

                        //     if (!$value->fruit_color) {
                                
                        //         $answer = AiGenerateText('only a list of colors, what colors are the fruits of '.$value->scientific_name[0],['temperature'=>0]);
                        //         $keyword = array("silver","gold","bronze","blue","green","red","yellow","purple","orange","teal","olive","azure","brown","gray","pink","maroon","violet","magenta","cream","tan","coral","burgundy","mauve","peach","indigo","ruby","cyan","black","white","gray");

                        //         $color = [];

                        //         foreach ($keyword as $token) {
                        //             if (stristr($answer, $token) !== FALSE) {
                        //                 $color[] = $token;
                        //             }
                        //         }

                        //         $array['fruit_color'] = $color;

                        //     }

                        // }catch(\Exception $e){

                        //     $columns = array($value->id,'fruit_color');
                        //     fputcsv($fp,$columns);

                        // }



                    // }


                // }else{

                //     try{

                //             if (!$value->edible_fruit) {
                                
                //                 $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' fruits edible for humans in 1 word?',['temperature'=>0]);

                //                 if (str_contains(strtolower(trim($answer)),'yes')) {
                //                     $array['edible'] = 1;
                //                 }

                //             }

                //         }catch(\Exception $e){

                //             $columns = array($value->id,'edible');
                //             fputcsv($fp,$columns);

                //         }

                //         try{

                //             if (!$value->harvest_season) {
                                
                //                 $answer = AiGenerateText('Picking Between Fall, Winter, Spring and Summer as choices, what season are '.$value->scientific_name[0].' fruits ready to be harvest in 1 word without period?',['temperature'=>0]);

                //                 if (str_contains('yes',strtolower($answer))) {
                //                     $array['harvest_season'] = trim($answer);
                //                 }

                //             }

                //         }catch(\Exception $e){

                //             $columns = array($value->id,'harvest_season');
                //             fputcsv($fp,$columns);

                //         }

                //         try{

                //             if (!$value->fruit_color) {
                                
                //                 $answer = AiGenerateText('only a list of colors, what colors are the fruits of '.$value->scientific_name[0],['temperature'=>0]);
                //                 $keyword = array("silver","gold","bronze","blue","green","red","yellow","purple","orange","teal","olive","azure","brown","gray","pink","maroon","violet","magenta","cream","tan","coral","burgundy","mauve","peach","indigo","ruby","cyan","black","white","gray");

                //                 $color = [];

                //                 foreach ($keyword as $token) {
                //                     if (stristr($answer, $token) !== FALSE) {
                //                         $color[] = $token;
                //                     }
                //                 }

                //                 $array['fruit_color'] = $color;

                //             }

                //         }catch(\Exception $e){

                //             $columns = array($value->id,'fruit_color');
                //             fputcsv($fp,$columns);

                //         }

                // }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'fruits');
            //     fputcsv($fp,$columns);

            // }


            // try{

            //     if (!$value->leaf) {

            //         $answer = AiGenerateText('yes or no, does '.$value->scientific_name[0].' also known as '.$value->common_name.' have leaves in 1 word?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['leaf'] = 1;

            //             try{

            //             if (!$value->edible_leaf) {
            //                 $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' leaves edible for humans in 1 word?',['temperature'=>0]);

            //                 if (str_contains(strtolower($answer),'yes')) {
            //                     $array['edible_leaf'] = 1;
            //                 }
            //             }

            //             }catch(\Exception $e){

            //                 $columns = array($value->id,'edible_leaf');
            //                 fputcsv($fp,$columns);

            //             }

            //             try{

            //             if (!$value->leaf_color) {
                            
            //                 $answer = AiGenerateText('only a list of colors, what colors are the leaves for '.$value->scientific_name[0].'?',['temperature'=>0]);
            //                 $keyword = array("silver","gold","bronze","green","red","yellow","purple","orange","teal","olive","azure","brown","gray","pink","maroon","violet","magenta","cream","tan","coral","burgundy","mauve","peach","indigo","ruby","cyan","black","white","gray");

            //                 $color = [];

            //                 foreach ($keyword as $token) {
            //                     if (stristr($answer, $token) !== FALSE) {
            //                         $color[] = $token;
            //                     }
            //                 }

            //                 $array['leaf_color'] = $color;

            //             }

            //             }catch(\Exception $e){

            //                 $columns = array($value->id,'leaf_color');
            //                 fputcsv($fp,$columns);

            //             }


            //         }

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'leaf');
            //     fputcsv($fp,$columns);

            // }


            // try{

            //     if (!$value->flowers) {
            //         $answer = AiGenerateText('yes or no, does '.$value->scientific_name[0].' also known as '.$value->common_name.' have flowers in 1 word?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['flowers'] = 1;

            //             try{

            //                 if (!$value->flowering_season) {
                                
            //                     $answer = AiGenerateText('Picking between Fall, Winter, Spring and Summer as choices, what season does '.$value->scientific_name[0].' start flowering in 1 word without period?',['temperature'=>0]);

            //                     $array['flowering_season'] = trim($answer);

            //                 }

            //             }catch(\Exception $e){

            //                 $columns = array($value->id,'flowering_season');
            //                 fputcsv($fp,$columns);

            //             }


            //         }
            //     }else{

            //         try{

            //             if (!$value->flowering_season) {
                            
            //                 $answer = AiGenerateText('Picking between Fall, Winter, Spring and Summer as choices, what season does '.$value->scientific_name[0].' start flowering in 1 word without period?',['temperature'=>0]);

            //                 $array['flowering_season'] = trim($answer);

            //             }

            //         }catch(\Exception $e){

            //             $columns = array($value->id,'flowering_season');
            //             fputcsv($fp,$columns);

            //         }

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'flowers');
            //     fputcsv($fp,$columns);

            // }






            try{

                if (!$value->seeds) {

                    $answer = AiGenerateText('yes or no, does '.$value->scientific_name[0].' also known as '.$value->common_name.' have seeds in 1 word?',['temperature'=>0]);

                    if (str_contains(strtolower($answer),'yes')) {
                        $array['seeds'] = 1;
                        $array['seeds_description'] = trim($answer);
                    }

                }

            }catch(\Exception $e){

                $columns = array($value->id,'seeds');
                fputcsv($fp,$columns);

            }






            // try{

            //     if (!$value->drought_tolerant) {
            //         $answer = AiGenerateText('Just yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' drought tolerant?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['drought_tolerant'] = 1;
            //         }
            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'drought_tolerant');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->salt_tolerant) {
                    
            //         $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' salt_tolerant in 1 word?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['salt_tolerant'] = 1;
            //         }

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'salt_tolerant');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->thorny) {
                    
            //         $answer = AiGenerateText('Just yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' thorny?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['thorny'] = 1;
            //         }

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'thorny');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->invasive) {
                    
            //         $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' invasive in 1 word?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['invasive'] = 1;
            //         }

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'invasive');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->rare) {

            //         $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' rare in 1 word?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['rare'] = 1;
            //         }

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'rare');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->medicinal) {

            //         $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' medicinal in 1 word?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['medicinal'] = 1;
            //         }

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'medicinal');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->tropical) {

            //         $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' tropical in 1 word?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['tropical'] = 1;
            //         }

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'tropical');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->cuisine) {

            //         $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' used in cooking in 1 word?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['cuisine'] = 1;
            //         }

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'cuisine');
            //     fputcsv($fp,$columns);

            // }

            // try{

            //     if (!$value->indoor) {

            //         $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' an indoor plant in 1 word?',['temperature'=>0]);

            //         if (str_contains(strtolower($answer),'yes')) {
            //             $array['indoor'] = 1;
            //         }

            //     }

            // }catch(\Exception $e){

            //     $columns = array($value->id,'indoor');
            //     fputcsv($fp,$columns);

            // }



            // Hardiness
            //Just numbers, what is the global hardiness zone for monstera?

            // Propagation
            //just the methods, what are ways to propogate for monstera in bullet points without description?

            Species::where('id',$value->id)->update($array);
            echo('Finish '.$value->id);
            
        }

        fclose($fp);

    }
}
