<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Species;
use Storage;

class SpeciesDetailsRevise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SpeciesDetailsRevise';

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


        $fp = fopen(Storage::disk('local')->path("textFile/speciesDetailRevise_".Carbon::now()->format('y-m-d').".csv"),'a');


        $filename = Storage::disk('local')->path("textFile/speciesDetail_23-01-20.csv");
        $file = fopen($filename, "r");
        $all_data = array();
        while ( ($data = fgetcsv($file)) !==FALSE ) {
            $all_data[] = $data;
        }

        foreach($all_data as $key => $data){

            if ($key == 0) {
                continue;
            }else{

                $text = "";

                $array = [];

                $value = Species::find($data[0]);

                if ($data[1]  == 'sunlight') {
            
                    try{
                        
                        if (!$value->sunlight) {
                            
                            $answer = AiGenerateText('between full shade, part shade, sun-part shade and full sun, how much sunlight does '.$value->scientific_name[0].' also known as '.$value->common_name.' need?',['temperature'=>0]);
                   
                            $keyword = array("full sun","part shade","part-shade","full-sun","full shade","full-shade","sun part shade","sun-part shade","sun-part-shade");

                            $color = [];

                            foreach ($keyword as $token) {
                                if (stristr($answer, $token) !== FALSE) {
                                    $color[] = $token;
                                }
                            }

                            $array['sunlight'] = $color;

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'sunlight');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'watering'){
                    
                    try{

                        if (!$value->watering) {

                            $answer = AiGenerateText('Picking between Average, Frequent and Minimal as choices, how much watering does '.$value->scientific_name[0].' also known as '.$value->common_name.' need in 1 word without period?',['temperature'=>0]);

                            $array['watering'] = ucfirst(trim($answer));

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'watering');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'cycle'){
                    
                    try{

                        if (!$value->cycle) {
                            $answer = AiGenerateText('Picking between biennial, biannual, perennial and annual as choices, what is the plant cycle for the plant species '.$value->scientific_name[0].' also known as '.$value->common_name.'?',['temperature'=>0]);

                            $array['cycle'] = ucfirst(trim($answer));
                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'cycle');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'growth_rate'){
                    
                    try{

                        if (!$value->growth_rate) {
                            $answer = AiGenerateText('Picking between High, Moderate and Low as choices, what is the growth rate for '.$value->scientific_name[0].' also known as '.$value->common_name.' in 1 word without period?',['temperature'=>0]);

                            $array['growth_rate'] = ucfirst(trim($answer));
                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'growth_rate');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'maintenance'){
                    
                    try{

                        if (!$value->maintenance) {
                            $answer = AiGenerateText('Picking between High, Moderate, Low and None as choices, how much maintenance does '.$value->scientific_name[0].' also known as '.$value->common_name.' need in 1 word without period?',['temperature'=>0]);

                            $array['maintenance'] = ucfirst(trim($answer));
                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'maintenance');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'care_level'){
                    
                    try{

                        if (!$value->care_level) {
                            $answer = AiGenerateText('Picking between Easy, Medium, Hard and Extreme as choices, what level of care does '.$value->scientific_name[0].' also known as '.$value->common_name.' does it need in 1 word without period?',['temperature'=>0]);

                            $array['care_level'] = ucfirst(trim($answer));
                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'care_level');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'poisonous_to_humans'){
                    
                    try{

                        if (!$value->poisonous_to_humans) {
                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' poisonous to humans in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['poisonous_to_humans'] = 1;
                            }
                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'poisonous_to_humans');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'poisonous_to_pets'){
                    
                    try{

                        if (!$value->poisonous_to_pets) {
                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' poisonous to pets in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['poisonous_to_pets'] = 1;
                            }
                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'poisonous_to_pets');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'cones'){
                    
                    try{

                        if (!$value->cones) {
                            $answer = AiGenerateText('yes or no, does '.$value->scientific_name[0].' also known as '.$value->common_name.' have cones in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower(trim($answer)),'yes')) {
                                $array['cones'] = 1;
                            }
                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'cones');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'fruits'){
                    
                    try{

                        if (!$value->fruits) {
                            
                            $answer = AiGenerateText('yes or no, does '.$value->scientific_name[0].' also known as '.$value->common_name.' have fruits in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower(trim($answer)),'yes')) {
                                $array['fruits'] = 1;

                                try{

                                    if (!$value->edible_fruit) {
                                        
                                        $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' fruits edible for humans in 1 word?',['temperature'=>0]);

                                        if (str_contains(strtolower(trim($answer)),'yes')) {
                                            $array['edible'] = 1;
                                        }

                                    }

                                }catch(\Exception $e){

                                    $columns = array($value->id,'edible');
                                    fputcsv($fp,$columns);

                                }

                                try{

                                    if (!$value->harvest_season) {
                                        
                                        $answer = AiGenerateText('Picking Between Fall, Winter, String and Summer as choices, what season are '.$value->scientific_name[0].' fruits ready to be harvest in 1 word without period?',['temperature'=>0]);

                                        if (str_contains('yes',strtolower($answer))) {
                                            $array['harvest_season'] = trim($answer);
                                        }

                                    }

                                }catch(\Exception $e){

                                    $columns = array($value->id,'harvest_season');
                                    fputcsv($fp,$columns);

                                }

                                try{

                                    if (!$value->fruit_color) {
                                        
                                        $answer = AiGenerateText('only a list of colors, what colors are the fruits of '.$value->scientific_name[0],['temperature'=>0]);
                                        $keyword = array("silver","gold","bronze","blue","green","red","yellow","purple","orange","teal","olive","azure","brown","gray","pink","maroon","violet","magenta","cream","tan","coral","burgundy","mauve","peach","indigo","ruby","cyan","black","white","gray");

                                        $color = [];

                                        foreach ($keyword as $token) {
                                            if (stristr($answer, $token) !== FALSE) {
                                                $color[] = $token;
                                            }
                                        }

                                        $array['fruit_color'] = $color;

                                    }

                                }catch(\Exception $e){

                                    $columns = array($value->id,'fruit_color');
                                    fputcsv($fp,$columns);

                                }



                            }


                        }else{

                                try{

                                    if (!$value->edible_fruit) {
                                        
                                        $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' fruits edible for humans in 1 word?',['temperature'=>0]);

                                        if (str_contains(strtolower(trim($answer)),'yes')) {
                                            $array['edible'] = 1;
                                        }

                                    }

                                }catch(\Exception $e){

                                    $columns = array($value->id,'edible');
                                    fputcsv($fp,$columns);

                                }

                                try{

                                    if (!$value->harvest_season) {
                                        
                                        $answer = AiGenerateText('Picking Between Fall, Winter, String and Summer as choices, what season are '.$value->scientific_name[0].' fruits ready to be harvest in 1 word without period?',['temperature'=>0]);

                                        if (str_contains('yes',strtolower($answer))) {
                                            $array['harvest_season'] = trim($answer);
                                        }

                                    }

                                }catch(\Exception $e){

                                    $columns = array($value->id,'harvest_season');
                                    fputcsv($fp,$columns);

                                }

                                try{

                                    if (!$value->fruit_color) {
                                        
                                        $answer = AiGenerateText('only a list of colors, what colors are the fruits of '.$value->scientific_name[0],['temperature'=>0]);
                                        $keyword = array("silver","gold","bronze","blue","green","red","yellow","purple","orange","teal","olive","azure","brown","gray","pink","maroon","violet","magenta","cream","tan","coral","burgundy","mauve","peach","indigo","ruby","cyan","black","white","gray");

                                        $color = [];

                                        foreach ($keyword as $token) {
                                            if (stristr($answer, $token) !== FALSE) {
                                                $color[] = $token;
                                            }
                                        }

                                        $array['fruit_color'] = $color;

                                    }

                                }catch(\Exception $e){

                                    $columns = array($value->id,'fruit_color');
                                    fputcsv($fp,$columns);

                                }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'fruits');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'edible'){
                
                    try{

                        if (!$value->edible_fruit) {
                            
                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' fruits edible for humans in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower(trim($answer)),'yes')) {
                                $array['edible'] = 1;
                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'edible');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'harvest_season'){
                    
                    try{

                        if (!$value->harvest_season) {
                            
                            $answer = AiGenerateText('Picking Between Fall, Winter, String and Summer as choices, what season are '.$value->scientific_name[0].' fruits ready to be harvest in 1 word without period?',['temperature'=>0]);

                            if (str_contains('yes',strtolower($answer))) {
                                $array['harvest_season'] = trim($answer);
                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'harvest_season');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'fruit_color'){
                    
                    try{

                        if (!$value->fruit_color) {
                            
                            $answer = AiGenerateText('only a list of colors, what colors are the fruits of '.$value->scientific_name[0],['temperature'=>0]);
                            $keyword = array("silver","gold","bronze","blue","green","red","yellow","purple","orange","teal","olive","azure","brown","gray","pink","maroon","violet","magenta","cream","tan","coral","burgundy","mauve","peach","indigo","ruby","cyan","black","white","gray");

                            $color = [];

                            foreach ($keyword as $token) {
                                if (stristr($answer, $token) !== FALSE) {
                                    $color[] = $token;
                                }
                            }

                            $array['fruit_color'] = $color;

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'fruit_color');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'leaf'){
                    
                    try{

                        if (!$value->leaf) {

                            $answer = AiGenerateText('yes or no, does '.$value->scientific_name[0].' also known as '.$value->common_name.' have leaves in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['leaf'] = 1;

                                try{

                                    if (!$value->edible_leaf) {
                                        $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' leaves edible for humans in 1 word?',['temperature'=>0]);

                                        if (str_contains(strtolower($answer),'yes')) {
                                            $array['edible_leaf'] = 1;
                                        }
                                    }

                                }catch(\Exception $e){

                                    $columns = array($value->id,'edible_leaf');
                                    fputcsv($fp,$columns);

                                }

                                try{

                                if (!$value->leaf_color) {
                                    
                                    $answer = AiGenerateText('only a list of colors, what colors are the leaves for '.$value->scientific_name[0].'?',['temperature'=>0]);
                                    $keyword = array("silver","gold","bronze","green","red","yellow","purple","orange","teal","olive","azure","brown","gray","pink","maroon","violet","magenta","cream","tan","coral","burgundy","mauve","peach","indigo","ruby","cyan","black","white","gray");

                                    $color = [];

                                    foreach ($keyword as $token) {
                                        if (stristr($answer, $token) !== FALSE) {
                                            $color[] = $token;
                                        }
                                    }

                                    $array['leaf_color'] = $color;

                                }

                                }catch(\Exception $e){

                                    $columns = array($value->id,'leaf_color');
                                    fputcsv($fp,$columns);

                                }


                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'leaf');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'edible_leaf'){
                    
                    try{

                        if (!$value->edible_leaf) {
                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' leaves edible for humans in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['edible_leaf'] = 1;
                            }
                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'edible_leaf');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'leaf_color'){
                    
                    try{

                    if (!$value->leaf_color) {
                        
                        $answer = AiGenerateText('only a list of colors, what colors are the leaves for '.$value->scientific_name[0].'?',['temperature'=>0]);
                        $keyword = array("silver","gold","bronze","green","red","yellow","purple","orange","teal","olive","azure","brown","gray","pink","maroon","violet","magenta","cream","tan","coral","burgundy","mauve","peach","indigo","ruby","cyan","black","white","gray");

                        $color = [];

                        foreach ($keyword as $token) {
                            if (stristr($answer, $token) !== FALSE) {
                                $color[] = $token;
                            }
                        }

                        $array['leaf_color'] = $color;

                    }

                    }catch(\Exception $e){

                        $columns = array($value->id,'leaf_color');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'flowers'){
                    
                    try{

                        if (!$value->flowers) {
                            $answer = AiGenerateText('yes or no, does '.$value->scientific_name[0].' also known as '.$value->common_name.' have flowers in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['flowers'] = 1;

                                try{

                                    if (!$value->flowering_season) {
                                        
                                        $answer = AiGenerateText('Picking between Fall, Winter, String and Summer as choices, what season does '.$value->scientific_name[0].' start flowering in 1 word without period?',['temperature'=>0]);

                                        $array['flowering_season'] = trim($answer);

                                    }

                                }catch(\Exception $e){

                                    $columns = array($value->id,'flowering_season');
                                    fputcsv($fp,$columns);

                                }


                            }
                        }else{

                            try{

                                if (!$value->flowering_season) {
                                    
                                    $answer = AiGenerateText('Picking between Fall, Winter, String and Summer as choices, what season does '.$value->scientific_name[0].' start flowering in 1 word without period?',['temperature'=>0]);

                                    $array['flowering_season'] = trim($answer);

                                }

                            }catch(\Exception $e){

                                $columns = array($value->id,'flowering_season');
                                fputcsv($fp,$columns);

                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'flowers');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'flowering_season'){
                    
                    try{

                        if (!$value->flowering_season) {
                            
                            $answer = AiGenerateText('Picking between Fall, Winter, String and Summer as choices, what season does '.$value->scientific_name[0].' start flowering in 1 word without period?',['temperature'=>0]);

                            $array['flowering_season'] = trim($answer);

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'flowering_season');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'drought_tolerant'){
            
                    try{

                        if (!$value->drought_tolerant) {
                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' drought tolerant in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['drought_tolerant'] = 1;
                            }
                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'drought_tolerant');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'salt_tolerant'){
                            
                    try{

                        if (!$value->salt_tolerant) {
                            
                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' salt_tolerant in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['salt_tolerant'] = 1;
                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'salt_tolerant');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'thorny'){
                        
                    try{

                        if (!$value->thorny) {
                            
                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' thorny in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['thorny'] = 1;
                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'thorny');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'invasive'){
                    
                    try{

                        if (!$value->invasive) {
                            
                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' invasive in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['invasive'] = 1;
                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'invasive');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'rare'){
                    
                    try{

                        if (!$value->rare) {

                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' rare in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['rare'] = 1;
                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'rare');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'medicinal'){
                            
                    try{

                        if (!$value->medicinal) {

                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' medicinal in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['medicinal'] = 1;
                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'medicinal');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'tropical'){
                 
                    try{

                        if (!$value->tropical) {

                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' tropical in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['tropical'] = 1;
                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'tropical');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'cuisine'){
                    
                    try{

                        if (!$value->cuisine) {

                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' used in cooking in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['cuisine'] = 1;
                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'cuisine');
                        fputcsv($fp,$columns);

                    }

                }elseif($data[1]  == 'indoor'){
                
                    try{

                        if (!$value->indoor) {

                            $answer = AiGenerateText('yes or no, are '.$value->scientific_name[0].' also known as '.$value->common_name.' an indoor plant in 1 word?',['temperature'=>0]);

                            if (str_contains(strtolower($answer),'yes')) {
                                $array['indoor'] = 1;
                            }

                        }

                    }catch(\Exception $e){

                        $columns = array($value->id,'indoor');
                        fputcsv($fp,$columns);

                    }

                }else{

                }

                Species::where('id',$data[0])->update($array);
                echo('Finish '.$data[0]);
                
            }

        }

        fclose($fp);











    }
}
