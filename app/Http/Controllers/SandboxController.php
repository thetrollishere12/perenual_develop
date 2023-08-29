<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Shuchkin\SimpleXLSX;
use App\Models\Species;
use App\Models\SpeciesTropical;
use App\Models\SpeciesIssue;
use App\Models\SpeciesCareGuide;

use App\Models\ArticleFaq;

use App\Models\EtsyMerchant;
use App\Models\GoogleMerchant;

use Intervention\Image\Facades\Image;
use Auth;
use DB;
use Http;

use App\Models\Country;

use App\Models\PropagationMethod;

class SandboxController extends Controller
{
    

    public function convert_dimension_to_array(){
        set_time_limit(0);
        $speciesData = Species::all();

        foreach ($speciesData as $specie) {
            // Matches the type if present (like "height", "width", etc.)
            preg_match('/(\w+):/', $specie->dimension, $typeMatches);
            $type = $typeMatches[1] ?? null;

            // Matches amounts and units
            preg_match_all('/(\d+(?:\.\d+)?)(?:\s*)(cm|feet)?/', $specie->dimension, $matches);

            $measurement = [];
            if (!empty($matches[1])) {
                $min_value = isset($matches[1][0]) ? floatval($matches[1][0]) : null;
                $max_value = isset($matches[1][1]) ? floatval($matches[1][1]) : $min_value; // if max value is not present, it's the same as min value
                $unit = $matches[2][0] ?? null;

                $measurement = [
                    'type' => $type,
                    'min_value' => $min_value,
                    'max_value' => $max_value,
                    'unit' => $unit
                ];
            }

            Species::find($specie->id)->update([
                'dimensions' => $measurement // convert array to JSON
            ]);

        }

        dd("Done Converting");

    }

    public function remove_species_duplicated_description(){

        $species = Species::select('description', \DB::raw('COUNT(*) as count'))
        ->groupBy('description')
        ->having('count', '>', 1)
        ->get();

        foreach ($species as $duplicate) {
            $description = $duplicate->description;
            
            Species::where('description', $description)->update(['description' => null]);
        }

    }

    // public function countries(){

    //     $countries = json_decode(Storage::disk('local')->get("json/backup.json"),true);

    //     foreach ($countries as $key => $country) {

    //         Country::updateOrCreate([
    //             'name'=>$country['name']
    //         ],[
    //             'name'=>$country['name'],
    //             'code'=>$country['code'],
    //             'currency'=>null,
    //             'country-code'=>$country['country-code'],
    //             'states'=>$country['states']
    //         ]);

    //     }

    // }

    public function unique_propagation(){

        $species = Species::all();

        $method = [];

        foreach ($species as $key => $value) {
            
            foreach ($value->propagation as $k => $propagation) {
                
                if(!in_array($propagation, $method)){

                    array_push($method,$propagation);

                }

            }

        }
        sort($method);

        foreach ($method as $k) {
            
            PropagationMethod::updateOrCreate([
                'name'=>$k
            ],[
                'name'=>$k
            ]);

        }

        dd($method);

    }

    public function null_to_array(){
        
        Species::where('soil',null)->orWhere('soil','[""]')->update([
            'soil'=>[]
        ]);

        Species::where('pest_susceptibility',null)->orWhere('pest_susceptibility','[""]')->update([
            'pest_susceptibility'=>[]
        ]);

        Species::where('other_name',null)->orWhere('other_name','[""]')->update([
            'other_name'=>[]
        ]);

        Species::where('scientific_name',null)->orWhere('scientific_name','[""]')->update([
            'scientific_name'=>[]
        ]);

        Species::where('propagation',null)->orWhere('propagation','[""]')->update([
            'propagation'=>[]
        ]);

        Species::where('origin',null)->orWhere('origin','[""]')->update([
            'origin'=>[]
        ]);

        Species::where('sunlight',null)->orWhere('sunlight','[""]')->update([
            'sunlight'=>[]
        ]);

        Species::where('fruit_color',null)->orWhere('fruit_color','[""]')->update([
            'fruit_color'=>[]
        ]);

        Species::where('leaf_color',null)->orWhere('leaf_color','[""]')->update([
            'leaf_color'=>[]
        ]);

        Species::where('attracts',null)->orWhere('attracts','[""]')->update([
            'attracts'=>[]
        ]);

        Species::where('family','')->update([
            'family'=>null
        ]);

    }

    public function imports(){

        set_time_limit(0);
        $db = DB::table('oldspecies')->where('harvest_season','!=',null)->get();

        foreach($db as $species){
            
            Species::where('id',$species->id)->update([
                'harvest_season' => $species->harvest_season
            ]);

        }

        // $db = Species::where('image','!=',null)->get();

        

        // foreach ($db as $species) {

                
                
        //         foreach ($species->image as $image) {
                    
        //             try{

        //             SpeciesImage::updateOrCreate([
        //                 'origin_url'=>$image
        //             ],[
        //                 'scientific_name'=>$species->scientific_name,
        //                 'origin_url'=>$image,
        //                 'folder'=>$species->folder,
        //                 'name'=>pathinfo(str_replace('_c.jpg','_b.jpg',$image))['filename'].'.jpg'
        //             ]);

        //             }catch(\Exception $e){
        //                 $columns = array($species->id,$species->common_name,$image);
        //                 fputcsv($fp,$columns);
        //             }

        //         }

            

        // }









        // $db = SpeciesImage::all();


        // foreach ($db as $species) {



        //     $name = pathinfo(str_replace('_c.jpg','_b.jpg',$species->name))['filename'];



        //    $t = Species::where('image','LIKE','%'.$name.'%')->get();

        //    if ($t->count() == 0) {
        //        dd($species);
        //    }


            
        // }







        // $db = DB::table('oldspecies')->get();

        // foreach($db as $species){
            
        //     Species::where('id',$species->id)->update([
        //         'cycle' => $species->cycle
        //     ]);

        // }

        // $db = DB::table('species')->whereNot('cycle',null)->get();

        // foreach($db as $species){
            
        //     if (str_contains(strtolower($species->cycle),'perennial')) {
            
        //         Species::where('id',$species->id)->update([
        //             'cycle' => 'Perennial'
        //         ]);

        //     }

        //     if (str_contains(strtolower($species->cycle),'annual')) {
            
        //         Species::where('id',$species->id)->update([
        //             'cycle' => 'Annual'
        //         ]);

        //     }

        //     if (str_contains(strtolower($species->cycle),'biennial')) {
            
        //         Species::where('id',$species->id)->update([
        //             'cycle' => 'Biennial'
        //         ]);

        //     }

        // }

    }

    public function google_merchant(){

        set_time_limit(0);

        $filename = Storage::disk('local')->path("merchant/us-output-scrapper.csv");

        $file = fopen($filename, "r");
        $all_data = array();
        while ( ($data = fgetcsv($file)) !==FALSE ) {
            $all_data[] = $data;
        }


        foreach($all_data as $key => $data){

            if ($key == 0) {
                continue;
            }else{

                $media = [];
                $open = [];

                if ($data[0]) {
                    $media['facebook'] = $data[0];
                }

                if ($data[1]) {
                    $media['instagram'] = $data[1];
                }

                if ($data[2]) {
                    $media['linkedin'] = $data[2];
                }

                if ($data[3]) {
                    $media['twitter'] = $data[3];
                }

                if ($data[4]) {
                    $media['tiktok'] = $data[4];
                }

                if ($data[5]) {
                    $media['youtube'] = $data[5];
                }

                if ($data[6]) {
                    $media['pinterest'] = $data[6];
                }

                if ($data[15]) {
                    $open['sunday'] = $data[15];
                }
                if ($data[16]) {
                    $open['monday'] = $data[16];
                }
                if ($data[17]) {
                    $open['tuesday'] = $data[17];
                }
                if ($data[18]) {
                    $open['wednesday'] = $data[18];
                }
                if ($data[19]) {
                    $open['thursday'] = $data[19];
                }
                if ($data[20]) {
                    $open['friday'] = $data[20];
                }
                if ($data[21]) {
                    $open['saturday'] = $data[21];
                }

                if (str_contains($data[13], 'Canada')) {
                    $country = 'Canada';
                }elseif (str_contains($data[13], 'USA')) {
                    $country = 'USA';
                }elseif (str_contains($data[13], 'United States')) {
                    $country = 'United States';
                }elseif (str_contains($data[13], 'United Kingdom')) {
                    $country = 'United Kingdom';
                }else{
                    $country = null;
                }

                try{

                GoogleMerchant::firstOrCreate(
                    ['address' => $data[10]],
                    [
                    'platform'=>'google',
                    'name'=>$data[7],
                    'rating'=>$data[8],
                    'review'=>$data[9],
                    'hours'=>$open,
                    'social_media'=>$media,
                    'website'=>$data[12],
                    'number'=>$data[11],
                    'country'=>$country,
                    'city'=>$data[22],
                    'province_county_state'=>$data[14],
                    'address'=>$data[10],
                    // 'shop_id'=>$data[14],
                    // 'sales'=>preg_replace('/[^0-9]/', '',$data[4]),
                    // 'rating'=>$data[2],
                    // 'review'=>$data[5],
                    // 'total_products'=>($data[7])? $data[7] :0,
                    // 'members'=>json_decode($data[8]),
                    // 'link'=>$data[1],
                    // 'social_media'=>$media,
                    // 'website'=>json_decode($data[13]),
                    // 'country'=>$data[15],
                    // 'location'=>$data[6]
                ]);

                }catch(\Exception $e){

                    dd($key);

                }


            }

        }


    }

    public function etsy_merchant(){
        set_time_limit(0);
        $filename = Storage::disk('local')->path("merchant/plants_ca_to_ca.csv");

        $file = fopen($filename, "r");
        $all_data = array();
        while ( ($data = fgetcsv($file)) !==FALSE ) {
            $all_data[] = $data;
        }

        foreach($all_data as $key => $data){

            if ($key == 0) {
                continue;
            }else{

                $media = [];

                if ($data[9]) {
                    $media['facebook'] = $data[9];
                }

                if ($data[10]) {
                    $media['instagram'] = $data[10];
                }

                if ($data[11]) {
                    $media['twitter'] = $data[11];
                }

                if ($data[12]) {
                    $media['pinterest'] = $data[12];
                }

                EtsyMerchant::firstOrCreate(
                    ['shop_id' => $data[14]],
                    [
                    'platform'=>'etsy',
                    'name'=>$data[0],
                    'shop_id'=>$data[14],
                    'sales'=>preg_replace('/[^0-9]/', '',$data[4]),
                    'rating'=>$data[2],
                    'review'=>$data[5],
                    'total_products'=>($data[7])? $data[7] :0,
                    'members'=>json_decode($data[8]),
                    'link'=>$data[1],
                    'social_media'=>$media,
                    'website'=>json_decode($data[13]),
                    'country'=>$data[15],
                    'location'=>$data[6]
                ]);

                
            }

        }

    }

    public function s_issue(){

        $filename = Storage::disk('local')->path("species_issues.csv");

        $file = fopen($filename, "r");
        $all_data = array();
        while ( ($data = fgetcsv($file)) !==FALSE ) {
            $all_data[] = $data;
        }

        foreach($all_data as $key => $data){

            $issue = SpeciesIssue::where('scientific_name',$data['3'])->where('common_name',$data['2'])->get();

            if ($issue->count() > 0) {

                SpeciesIssue::updateOrCreate(
                    [
                        'common_name' => $data['2'],
                        'scientific_name' => $data['3']],
                    [
                        'image'=>explode(",",str_replace(['["','"]'],'',str_replace("\/","/",$data['10'])))
                    ]
                );

            }

        }

        SpeciesIssue::where('image','LIKE','%null%')->update([
            'image'=>null
        ]);

    }

    public function issue(){



        $filename = Storage::disk('local')->path("plant_disease.csv");

        $file = fopen($filename, "r");
        $all_data = array();
        while ( ($data = fgetcsv($file)) !==FALSE ) {
            $all_data[] = $data;
        }


        foreach($all_data as $key => $data){

            if ($key == 0) {
                continue;
            }else{

                $issue = SpeciesIssue::where('scientific_name',$data['3'])->where('scientific_name','!=',"")->get();

                if ($issue->count() > 0) {
                    
                    $h = $issue->first()->host;

                    if(!in_array($data['1'], $h)){

                        array_push($h,$data['1']);

                    }


                    $o = $issue->first()->other_name;

                    if ($issue->first()->scientific_name == $data['3'] && $issue->first()->common_name != $data['2']) {
                        
                        if ($o == null) {
                      
                            $o = [$data['2']];

                        }else{

                            if(!in_array($data['2'], $o)){

                                array_push($o,$data['2']);

                            }

                        }

                    }

                    SpeciesIssue::updateOrCreate(
                        [
                            'scientific_name' => $data['3']],
                        [
                            'scientific_name'=>$data['3'],
                            'other_name'=>$o,
                            'family'=>null,
                            'type'=>$data['4'],
                            'host'=>$h,
                            'copyright_images'=>explode(',',str_replace(["[","]","'"], '',$data['5']))
                        ]
                    );

                }else{

                    if ($data['3'] == "") {
                        
                        $issue = SpeciesIssue::where('common_name',$data['2'])->where('scientific_name','=',"")->get();

                        if ($issue->count() > 0) {

                            $h = $issue->first()->host;

                            if(!in_array($data['1'], $h)){

                                array_push($h,$data['1']);

                            }


                            $o = $issue->first()->other_name;

                            if ($issue->first()->scientific_name == $data['3'] && $issue->first()->common_name != $data['2']) {
                                
                                if ($o == null) {
                              
                                    $o = [$data['2']];

                                }else{

                                    if(!in_array($data['2'], $o)){

                                        array_push($o,$data['2']);

                                    }

                                }

                            }





                            SpeciesIssue::updateOrCreate(
                                [
                                    'common_name' => $data['2'],
                                    'scientific_name' => $data['3']],
                                [
                                    'common_name' => $data['2'],
                                    'scientific_name'=>$data['3'],
                                    'other_name'=>$o,
                                    'family'=>null,
                                    'type'=>$data['4'],
                                    'host'=>$h,
                                    'copyright_images'=>explode(',',str_replace(["[","]","'"], '',$data['5']))
                                ]
                            );





                        }else{

                            SpeciesIssue::create(
                                [
                                    'common_name' => $data['2'],
                                    'scientific_name'=>$data['3'],
                                    'family'=>null,
                                    'type'=>$data['4'],
                                    'host'=>[$data['1']],
                                    'copyright_images'=>explode(',',str_replace(["[","]","'"], '',$data['5']))
                                ]
                            );

                        }

                        

                    }else{

                        SpeciesIssue::create(
                            [
                                'common_name' => $data['2'],
                                'scientific_name'=>$data['3'],
                                'family'=>null,
                                'type'=>$data['4'],
                                'host'=>[$data['1']],
                                'copyright_images'=>explode(',',str_replace(["[","]","'"], '',$data['5']))
                            ]
                        );

                    }

                }

            }

        }

        // $species = Species::all();

        // $issue = [];
        // foreach($species as $species){

        //     if (isset($species->pest_susceptibility)) {

        //         foreach($species->pest_susceptibility as $key => $issues) {
        //             $issue[] = trim($issues);
        //         }

        //     }

        // }

        // $uniques = array_unique($issue);

        // foreach($uniques as $unique){

        //     if ($unique != "") {
                
        //         SpeciesIssue::updateOrCreate(
        //             ['name' => $unique],
        //             [
        //                 'name' => $unique,
        //                 'description'=> AiGenerateText('Write paragraphs about a plant disease or pest called '.$unique.' without solutions or treatments',[]),
        //                 'solution' => AiGenerateText('Write paragraphs about solutions for plant disease or pest called '.$unique,[]),
        //                 'seen' => 0,
        //                 'helpful' => 0
        //             ]
        //         );
        //     }

        //     break;

        // }

    }

    function multiexplode($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    public function tropical(){

        set_time_limit(0);
        if ($xlsx = SimpleXLSX::parse('storage/species/tropical.xlsx') ) {
            foreach ($xlsx->rows() as $key => $value) {

                // dd($value);

                if ($key != 0) {

                    if (str_contains('z-blank',$value[2])) {
                        continue;
                    }

                    $hardiness = explode('to',str_replace(['Zone',' '],'',$value[13]));

                    if (count($hardiness) > 1) {

                        $hardiness = [
                            'min'=> preg_replace("/[^0-9]/", "",$hardiness[0]),
                            'max'=> preg_replace("/[^0-9]/", "",end($hardiness))
                        ];

                    }elseif(count($hardiness) == 1){
                        $hardiness = [
                            'min' => preg_replace("/[^0-9]/", "",$hardiness[0]),
                            'max' => preg_replace("/[^0-9]/", "",end($hardiness))
                        ];
                    }else{
                        $hardiness = null;
                    }
// dd($hardiness);
                    $flower_season = explode(',',str_replace(['Zone',' '],'',$value[15]));
                
                    switch (true) {
                        case ucfirst($flower_season[0]) == 'Jun' || ucfirst($flower_season[0]) == 'Jul' || ucfirst($flower_season[0]) == 'Aug' || ucfirst($flower_season[0]) == 'June' || ucfirst($flower_season[0]) == 'July' || ucfirst($flower_season[0]) == 'Auguest':
                            $flower_season = "Summer";
                            break;
                        case ucfirst($flower_season[0]) == 'Mar' || ucfirst($flower_season[0]) == 'Apr' || ucfirst($flower_season[0]) == 'May' || ucfirst($flower_season[0]) == 'March' || ucfirst($flower_season[0]) == 'April':
                            $flower_season = "Spring";
                            break;
                        case ucfirst($flower_season[0]) == 'Sep' || ucfirst($flower_season[0]) == 'Oct' || ucfirst($flower_season[0]) == 'Nov' || ucfirst($flower_season[0]) == 'September' || ucfirst($flower_season[0]) == 'October' || ucfirst($flower_season[0]) == 'November':
                            $flower_season = "Autumn";
                            break;
                        case ucfirst($flower_season[0]) == 'Dec' || ucfirst($flower_season[0]) == 'Jan' || ucfirst($flower_season[0]) == 'Feb' || ucfirst($flower_season[0]) == 'December' || ucfirst($flower_season[0]) == 'January' || ucfirst($flower_season[0]) == 'February':
                            $flower_season = "Winter";
                            break;
                        
                        default:
                            $flower_season = null;
                            break;
                    }


                    $fruiting_season = explode(',',str_replace(['Zone',' '],'',$value[21]));
                
                    switch (true) {
                        case ucfirst($fruiting_season[0]) == 'Jun' || ucfirst($fruiting_season[0]) == 'Jul' || ucfirst($fruiting_season[0]) == 'Aug' || ucfirst($fruiting_season[0]) == 'June' || ucfirst($fruiting_season[0]) == 'July' || ucfirst($fruiting_season[0]) == 'Auguest':
                            $fruiting_season = "Summer";
                            break;
                        case ucfirst($fruiting_season[0]) == 'Mar' || ucfirst($fruiting_season[0]) == 'Apr' || ucfirst($fruiting_season[0]) == 'May' || ucfirst($fruiting_season[0]) == 'March' || ucfirst($fruiting_season[0]) == 'April':
                            $fruiting_season = "Spring";
                            break;
                        case ucfirst($fruiting_season[0]) == 'Sep' || ucfirst($fruiting_season[0]) == 'Oct' || ucfirst($fruiting_season[0]) == 'Nov' || ucfirst($fruiting_season[0]) == 'September' || ucfirst($fruiting_season[0]) == 'October' || ucfirst($fruiting_season[0]) == 'November':
                            $fruiting_season = "Autumn";
                            break;
                        case ucfirst($fruiting_season[0]) == 'Dec' || ucfirst($fruiting_season[0]) == 'Jan' || ucfirst($fruiting_season[0]) == 'Feb' || ucfirst($fruiting_season[0]) == 'December' || ucfirst($fruiting_season[0]) == 'January' || ucfirst($fruiting_season[0]) == 'February':
                            $fruiting_season = "Winter";
                            break;
                        
                        default:
                            $fruiting_season = null;
                            break;
                    }
                
                    switch (true) {
            
                        case str_contains(strtolower($value[9]),'flood') || str_contains(strtolower($value[9]),'wetlands') || str_contains(strtolower($value[9]),'wet') || str_contains($value[9],'high') || str_contains($value[9],'aquatic') || str_contains(strtolower($value[9]),'Frequent'):
                            $watering = "Frequent";
                            break;
                        case str_contains(strtolower($value[9]),'drought') || str_contains(strtolower($value[9]),'low') || str_contains(strtolower($value[9]),'dry') || str_contains(strtolower($value[9]),'Minimum'):
                            $watering = "Minimum";
                            break;
                        case str_contains(strtolower($value[9]),'normal') || str_contains(strtolower($value[9]),'medium') || str_contains(strtolower($value[9]),'normal') || str_contains(strtolower($value[9]),'moderate'):
                            $watering = "Average";
                            break;
                        default:
                            $watering = "Average";
                            break;
                    }

                    switch (true) {
            
                        case str_contains(strtolower($value[23]),'moderate') || str_contains(strtolower($value[23]),'wetlands') || str_contains(strtolower($value[23]),'wet') || str_contains(strtolower($value[23]),'high') || str_contains(strtolower($value[23]),'fast'):
                            $growth_rate = "High";
                            break;
                        case str_contains(strtolower($value[23]),'low') || str_contains(strtolower($value[23]),'slow'):
                            $growth_rate = "Low";
                            break;
                        case str_contains(strtolower($value[23]),'medium') || str_contains(strtolower($value[23]),'normal')|| str_contains(strtolower($value[23]),'moderate'):
                            $growth_rate = "Moderate";
                            break;
                        default:
                            $growth_rate = "Moderate";
                            break;
                    }


                    switch (true) {
            
                        case str_contains(strtolower($value[24]),'wetlands') || str_contains(strtolower($value[24]),'wet') || str_contains(strtolower($value[24]),'high') || str_contains(strtolower($value[24]),'aquatic'):
                            $maintenance = "High";
                            break;
                        case str_contains(strtolower($value[24]),'low') || str_contains(strtolower($value[24]),'dry'):
                            $maintenance = "Low";
                            break;
                        case str_contains(strtolower($value[24]),'medium') || str_contains(strtolower($value[24]),'normal') || str_contains(strtolower($value[24]),'moderate'):
                            $maintenance = "Moderate";
                            break;
                        default:
                            $maintenance = "Moderate";
                            break;
                    }

                    if (str_contains($value[0],'https://naturaledge.watersheds.ca')) {
                        $soil = explode(' ',$value[23]);
                    }else{
                        $soil = explode(',',str_replace('or',',',$value[23]));
                    }

                    
                    $scientific_name = explode(',',$value[2]);

                    $skip = false;
                    foreach($scientific_name as $s_name){

                        $species = Species::where('scientific_name','LIKE','%'.trim($s_name).'%')->first();

                        if ($species) {
                            $skip = true;
                            continue;
                        }

                    }

                    if ($skip == true) {
                        continue;
                    }
                    
                    $skip = false;
                    foreach($scientific_name as $s_name){

                        $species = SpeciesTropical::where('scientific_name','LIKE','%'.trim($s_name).'%')->first();

                        if ($species) {
                            $skip = true;
                            continue;
                        }

                    }

                    if ($skip == true) {
                        continue;
                    }

                    if ($species) {
                    
                        SpeciesTropical::create([
                            'common_name' => $value[1],
                            'scientific_name' => $value[2],
                            'other_name'=> ($value[3])? str_replace('Other Names: ','',explode(',',$value[3])) : null,
                            'family'=>$value[4],
                            'origin'=>($value[5])? explode(',',$value[5]) : null,
                            'type'=>$value[6],
                            'dimension'=>$value[7],
                            'cycle'=>$value[8],
                            'watering'=>$watering,
                            'edible_fruit'=>null,
                            'attracts'=>$value[11],
                            'propagation'=> ($value[12])? explode(',',$value[12]) : null,
                            'hardiness'=> $hardiness,
                            'flowers'=> ($value[14] && $value[14] != "Insignificant")? true : 0,
                            'flowering_season'=>($value[14] && $value[14] != "Insignificant")? $flower_season : null,
                            'color'=>($value[14] && $value[14] != "Insignificant")? $value[16] : null,
                            'sun_exposure'=>$value[17],
                            'soil'=> ($value[18])? $soil : null,
                            'pest_susceptibility'=> ($value[19])? str_replace(['(',')'],"",$this->multiexplode([',',' or '],$value[19])) : null,
                            'fruits'=> ($value[20])? true : 0,
                            'fruiting_season'=> $fruiting_season,
                            'poisonous'=>$value[22],
                            'growth_rate'=>$growth_rate,
                            'maintenance'=>$maintenance,
                            'image'=>$value[25],
                            'description'=>$value[26]
                        ]);

                    }
                }
            }
        }

    }

    public function xlsx(){
        set_time_limit(0);
        if ( $xlsx = SimpleXLSX::parse('storage/species/data3.xlsx') ) {

            foreach ($xlsx->rows() as $key => $value) {

                if (str_contains('z-blank',$value[3]) || $value[1] == "") {
                    continue;
                }

                if ($key != 0) {

                    $hardiness = explode('to',str_replace(['Zone',' '],'',$value[15]));

                    if (count($hardiness) > 1) {

                        $hardiness = [
                            'min'=> preg_replace("/[^0-9]/", "",$hardiness[0]),
                            'max'=> preg_replace("/[^0-9]/", "",$hardiness[1])
                        ];

                    }elseif(count($hardiness) == 1){
                        $hardiness = [
                            'min' => preg_replace("/[^0-9]/", "",$hardiness[0]),
                            'max' => preg_replace("/[^0-9]/", "",$hardiness[0])
                        ];
                    }else{
                        $hardiness = null;
                    }

                    $flower_season = explode(',',str_replace(['Zone',' '],'',$value[18]));
                
                    switch (true) {
                        case ucfirst($flower_season[0]) == 'Jun' || ucfirst($flower_season[0]) == 'Jul' || ucfirst($flower_season[0]) == 'Aug' || ucfirst($flower_season[0]) == 'June' || ucfirst($flower_season[0]) == 'July' || ucfirst($flower_season[0]) == 'Auguest':
                            $flower_season = "Summer";
                            break;
                        case ucfirst($flower_season[0]) == 'Mar' || ucfirst($flower_season[0]) == 'Apr' || ucfirst($flower_season[0]) == 'May' || ucfirst($flower_season[0]) == 'March' || ucfirst($flower_season[0]) == 'April':
                            $flower_season = "Spring";
                            break;
                        case ucfirst($flower_season[0]) == 'Sep' || ucfirst($flower_season[0]) == 'Oct' || ucfirst($flower_season[0]) == 'Nov' || ucfirst($flower_season[0]) == 'September' || ucfirst($flower_season[0]) == 'October' || ucfirst($flower_season[0]) == 'November':
                            $flower_season = "Autumn";
                            break;
                        case ucfirst($flower_season[0]) == 'Dec' || ucfirst($flower_season[0]) == 'Jan' || ucfirst($flower_season[0]) == 'Feb' || ucfirst($flower_season[0]) == 'December' || ucfirst($flower_season[0]) == 'January' || ucfirst($flower_season[0]) == 'February':
                            $flower_season = "Winter";
                            break;
                        
                        default:
                            $flower_season = null;
                            break;
                    }


                    $fruiting_season = explode(',',str_replace(['Zone',' '],'',$value[39]));
                
                    switch (true) {
                        case ucfirst($fruiting_season[0]) == 'Jun' || ucfirst($fruiting_season[0]) == 'Jul' || ucfirst($fruiting_season[0]) == 'Aug' || ucfirst($fruiting_season[0]) == 'June' || ucfirst($fruiting_season[0]) == 'July' || ucfirst($fruiting_season[0]) == 'Auguest':
                            $fruiting_season = "Summer";
                            break;
                        case ucfirst($fruiting_season[0]) == 'Mar' || ucfirst($fruiting_season[0]) == 'Apr' || ucfirst($fruiting_season[0]) == 'May' || ucfirst($fruiting_season[0]) == 'March' || ucfirst($fruiting_season[0]) == 'April':
                            $fruiting_season = "Spring";
                            break;
                        case ucfirst($fruiting_season[0]) == 'Sep' || ucfirst($fruiting_season[0]) == 'Oct' || ucfirst($fruiting_season[0]) == 'Nov' || ucfirst($fruiting_season[0]) == 'September' || ucfirst($fruiting_season[0]) == 'October' || ucfirst($fruiting_season[0]) == 'November':
                            $fruiting_season = "Autumn";
                            break;
                        case ucfirst($fruiting_season[0]) == 'Dec' || ucfirst($fruiting_season[0]) == 'Jan' || ucfirst($fruiting_season[0]) == 'Feb' || ucfirst($fruiting_season[0]) == 'December' || ucfirst($fruiting_season[0]) == 'January' || ucfirst($fruiting_season[0]) == 'February':
                            $fruiting_season = "Winter";
                            break;
                        
                        default:
                            $fruiting_season = null;
                            break;
                    }
                
                    switch (true) {
            
                        case str_contains(strtolower($value[26]),'flood') || str_contains(strtolower($value[9]),'wetlands') || str_contains(strtolower($value[9]),'wet') || str_contains($value[9],'high') || str_contains($value[9],'aquatic') || str_contains(strtolower($value[9]),'Frequent'):
                            $watering = "Frequent";
                            break;
                        case str_contains(strtolower($value[26]),'drought') || str_contains(strtolower($value[9]),'low') || str_contains(strtolower($value[9]),'dry') || str_contains(strtolower($value[9]),'Minimum'):
                            $watering = "Minimum";
                            break;
                        case str_contains(strtolower($value[26]),'normal') || str_contains(strtolower($value[9]),'medium') || str_contains(strtolower($value[9]),'normal') || str_contains(strtolower($value[9]),'moderate'):
                            $watering = "Average";
                            break;
                        default:
                            $watering = null;
                            break;
                    }

                    switch (true) {
            
                        case  str_contains(strtolower($value[51]),'high') || str_contains(strtolower($value[51]),'fast'):
                            $growth_rate = "High";
                            break;
                        case str_contains(strtolower($value[51]),'low') || str_contains(strtolower($value[51]),'slow'):
                            $growth_rate = "Low";
                            break;
                        case str_contains(strtolower($value[51]),'medium') || str_contains(strtolower($value[52]),'average') || str_contains(strtolower($value[51]),'normal')|| str_contains(strtolower($value[51]),'moderate'):
                            $growth_rate = "Moderate";
                            break;
                        default:
                            $growth_rate = null;
                            break;
                    }


                    switch (true) {
            
                        case str_contains(strtolower($value[52]),'high'):
                            $maintenance = "High";
                            break;
                        case str_contains(strtolower($value[52]),'low') || str_contains(strtolower($value[52]),'slow'):
                            $maintenance = "Low";
                            break;
                        case str_contains(strtolower($value[52]),'medium') || str_contains(strtolower($value[52]),'average') || str_contains(strtolower($value[52]),'normal') || str_contains(strtolower($value[52]),'moderate'):
                            $maintenance = "Moderate";
                            break;
                        default:
                            $maintenance = null;
                            break;
                    }

                    if (str_contains($value[0],'https://naturaledge.watersheds.ca')) {
                        $soil = array_filter(explode(' ',$value[23]));
                    }else{
                        $soil = array_filter(explode(',',str_replace('or',',',$value[23])));
                    }

                    $scientific_name = str_replace([' × '],' ',$value[3]);
                    
                    $scientific_name = str_replace(['ü'],'u',$scientific_name);

                    $species = Species::where('scientific_name','LIKE','%'.$scientific_name.'%')->first();

                    $pest = array_filter(str_replace(['(',')'],"",$this->multiexplode([',',' or '],$value[47])));

                    $sunlight = array_filter($this->multiexplode([',',' or ',' to '],$value[21]));

                    if ($species) {
                        
                        $other_name = str_replace('Other Names: ','',explode(',',$value[2]));

                        $array_name = $species->other_name;

                        if($species->other_name != null){
                            foreach($other_name as $name){

                                if(!in_array($name,$species->other_name)){
                                    
                                    if ($name != "") {

                                        $array_name[] = trim($name);

                                    }

                                }

                            }
                        }

                        $array = [];

                        if($array_name != null){
                            $array['other_name'] = array_unique($array_name);
                        }

                        if (!$species->family && $species->family != "" && $value[4] != "") {
                            $array['family'] = $value[4];
                        }

                        if (!$species->origin && $species->origin != "" && $value[5] != "") {
                            $array['origin'] = explode(',',$value[5]);
                        }

                        if (!$species->type && $species->type != "" && $value[6] != "") {
                            $array['type'] = $value[6];
                        }

                        if (!$species->dimension && $species->dimension != "" && $value[35] != "") {
                            $array['dimension'] = $value[35];
                        }

                        if (!$species->cycle && $species->cycle != "" && $value[8] != "") {
                            $array['cycle'] = $value[8];
                        }

                        if (!$species->watering && $species->watering != "" && $watering != "") {
                            $array['watering'] = $watering;
                        }

                        if (!$species->attracts && $species->attracts != [] && $value[11] != "") {
                            $array['attracts'] = explode(',',$value[11]);
                        }

                        if (!$species->propagation && $species->propagation != [] && $value[13] != "") {
                            $array['propagation'] = explode(',',$value[13]);
                        }

                        if (!$species->hardiness && $species->hardiness != [] && $hardiness != "") {
                            $array['hardiness'] = $hardiness;
                        }

                        if (!$species->flowers && $species->flowers != "" && $value[18] != "") {
                            if ($value[18] != "") {
                                $array['flowers'] = true;
                            }
                        }

                        if (!$species->flowering_season && $species->flowering_season != "") {
                            if ($value[18] != "") {
                                $array['flowering_season'] = $flower_season;
                            }
                        }

                        if (!$species->color && $species->color != "" && $value[19] != "") {
                            $array['color'] = $value[19];
                        }

                        if (!$species->sunlight && $species->sunlight != [] && $sunlight != []) {
                            $array['sunlight'] = array_filter($sunlight);
                        }

                        if (!$species->soil && $species->soil != [] && $soil != []) {
                            $array['soil'] = $soil;
                        }

                        if (!$species->problem && $species->problem != "" && $value[24] != "") {
                            $array['problem'] = $value[24];
                        }

                        if (!$species->pest_susceptibility && $species->pest_susceptibility != [] && $pest != []) {
                            $array['pest_susceptibility'] = $pest;
                        }

                        if (!$species->fruits && $species->fruits != "" && $value[38] != "") {
                            $array['fruits'] = true;
                        }

                        if (!$species->fruiting_season && $species->fruiting_season != "" && $value[38] != "") {
                            $array['fruiting_season'] = $fruiting_season;
                        }

                        if (!$species->growth_rate && $species->growth_rate != "" && $growth_rate != "") {
                            $array['growth_rate'] = $growth_rate;
                        }

                        if (!$species->maintenance && $species->maintenance != "" && $maintenance != "") {
                            $array['maintenance'] = $maintenance;
                        }

                        if (!$species->copyright_image && $species->copyright_image != "" && $value[53] != "") {
                            $array['copyright_image'] = $value[53];
                        }

                        if (!$species->copyright_image2 && $species->copyright_image2 != "" && $value[54] != "") {
                            $array['copyright_image2'] = $value[54];
                        }

                        if (!$species->description && $species->description != "" && $value[55] != "") {
                            $array['description'] = $value[55];
                        }

                        // Species::where('scientific_name','LIKE','%'.$scientific_name.'%')->update($array);

                        // Species::where('scientific_name','LIKE','%'.$scientific_name.'%')->update([
                        //     'other_name'=> $array_name,
                        //     'family'=>($species->family)? $species->family : $value[4],
                        //     'origin'=>($species->origin)? $species->origin : explode(',',$value[5]),
                        //     'type'=>($species->type)? $species->type : $value[6],
                        //     'dimension'=>($species->dimension)? $species->dimension : $value[35],
                        //     'cycle'=>($species->cycle)? $species->cycle : $value[8],
                        //     'watering'=>($species->watering)? $species->watering : $watering,
                        //     'attracts'=>($species->attracts)? $species->attracts : explode(',',$value[11]),
                        //     'propagation'=> ($species->propagation)? $species->propagation : explode(',',$value[13]),
                        //     'hardiness'=> ($species->hardiness)? $species->hardiness : $hardiness,
                        //     'flowers'=> ($species->flowers)? $species->flowers : true,
                        //     'flowering_season'=>($species->flower_season)? $species->flower_season : $flower_season,
                        //     'color'=>($species->color)? $species->color : $value[19],
                        //     'sunlight'=>($species->sun_exposure)? $species->sun_exposure : array_filter($sunlight),
                        //     'soil'=> ($species->soil)? $species->soil : $soil,
                        //     'problem' => ($species->problem)? $species->problem : $value[24],
                        //     'pest_susceptibility'=> ($species->pest_susceptibility)? $species->pest_susceptibility : array_filter($pest),
                        //     'fruits'=> ($species->fruits)? $species->fruits : true,
                        //     'fruiting_season'=> ($species->fruiting_season)? $species->fruiting_season : $fruiting_season,
                        //     'growth_rate'=>($species->growth_rate)? $species->growth_rate : $growth_rate,
                        //     'maintenance'=> ($species->maintenance)? $species->maintenance : $maintenance,
                        //     'copyright_image'=>($species->copyright_image)? $species->copyright_image : $value[53],
                        //     'copyright_image2'=>($species->copyright_image2)? $species->copyright_image2 : $value[54],
                        //     'description'=>($species->description)? $species->type : $value[55]
                        // ]);

                    }else{


                        $other_name = str_replace('Other Names: ','',explode(',',$value[2]));

                        $array_name = [];
                        
                        foreach($other_name as $name){
                                
                            if ($name != "") {

                                $array_name[] = trim($name);

                            }
                        

                        }
                        
                        $array = [];

                        if($array_name != null){
                            $array['other_name'] = array_unique($array_name);
                        }

                        if ($value[4] != "") {
                            $array['family'] = $value[4];
                        }

                        if ($value[5] != "") {
                            $array['origin'] = explode(',',$value[5]);
                        }

                        if ($value[6] != "") {
                            $array['type'] = $value[6];
                        }

                        if ($value[35] != "") {
                            $array['dimension'] = $value[35];
                        }

                        if ($value[8] != "") {
                            $array['cycle'] = $value[8];
                        }

                        if ($watering != "") {
                            $array['watering'] = $watering;
                        }

                        if ($value[11] != "") {
                            $array['attracts'] = explode(',',$value[11]);
                        }

                        if ($value[13] != "") {
                            $array['propagation'] = explode(',',$value[13]);
                        }

                        if ($hardiness != "") {
                            $array['hardiness'] = $hardiness;
                        }

                        if ($value[18] != "") {
                            $array['flowers'] = true;
                        }

                        if ($value[18] != "") {
                            $array['flowering_season'] = $flower_season;
                        }

                        if ($value[19] != "") {
                            $array['color'] = $value[19];
                        }

                        if ($sunlight != []) {
                            $array['sunlight'] = $sunlight;
                        }

                        if ($soil != []) {
                            $array['soil'] = $soil;
                        }else{
                            $array['soil'] = null;
                        }

                        if ($value[24] != "") {
                            $array['problem'] = $value[24];
                        }

                        if ($pest != []) {
                            $array['pest_susceptibility'] = $pest;
                        }

                        if ($value[38] != "") {
                            $array['fruits'] = true;
                        }

                        if ($value[38] != "") {
                            $array['fruiting_season'] = $fruiting_season;
                        }

                        if ($growth_rate != "") {
                            $array['growth_rate'] = $growth_rate;
                        }

                        if ($maintenance != "") {
                            $array['maintenance'] = $maintenance;
                        }

                        if ($value[53] != "") {
                            $array['copyright_image'] = $value[53];
                        }

                        if ($value[54] != "") {
                            $array['copyright_image2'] = $value[54];
                        }

                        if ($value[55] != "") {
                            $array['description'] = $value[55];
                        }

                        $array['common_name'] = $value[1];
                        $array['scientific_name'] = explode(',',$scientific_name);

                        Species::create($array);

                    }

                }

            }

        } else {
            echo SimpleXLSX::parseError();
        }

    }

    public function index(){

        $store = etsy_get_store(5124650);

        dd($store->user_id);

        $filename = Storage::disk('public')->path('sellers/etsy/data_etsy_og_3.csv');
        $file = fopen($filename, "r");
        $all_data = array();
        while ( ($data = fgetcsv($file)) !==FALSE ) {
            $all_data[] = $data;
        }

        return view('sandbox.index',['datas'=>$all_data]);

    }

    public function image_post(){

        set_time_limit(0);
        
        $data = [
            "undertitle"=>"",
            "name"=>"Begonia",
            "subtitle"=>[
                [   "title_search"=> "Watering",
                    "title" => "Watering.",
                    "type" => "1 sentence"
                ],
                [
                    "title_search"=> "Sunlight exposure",
                    "title" => "Sunlight.",
                    "type" => "1 sentence"
                ],
                [   "title_search"=> "pest and diseases",
                    "title" => "Pest & Dieases.",
                    "type" => "2 short sentences"
                ],
                [   "title_search"=> "soil type",
                    "title" => "Soil.",
                    "type" => "2 short sentences"
                ],
                [   "title_search"=> "benefits",
                    "title" => "Benefits.",
                    "type" => "2 short points"
                ]
            ],
            "description"=>[]
        ];

        foreach($data['subtitle'] as $key => $subtitle){

            $data['description'][$key] = ltrim(AiGenerateText('Write '.$subtitle['type'].' about a plant called '.$data['name'].' on '.$subtitle['title_search'],[]));

        }

        // AI Question - just from a scale of 1 - 10 without any words, how much sunlight does a blueberry need?

        // foreach($data['subtitle'] as $key => $subtitle){

        //     $data['description'][$key] = 'TEST PLANT is a low-maintenance and easy to care for plant that adapts to a variety of environments.';

        // }

        $images = [];


        // $Ai = AiGenerateImg('Aesthetic '.$data['name'].' Plant with light gray background');

        // foreach($Ai->data as $image){

        //     $images[] = [
        //         'url'=>$image->url,
        //     ];

        //     $id = random_id('AI_I_');
        //     Storage::disk('local')->put('OpenAi/'.$data['name'].'_'.$id.'.jpg',file_get_contents($image->url));

        // }


        // $unsplashes = [UnsplashImages($data['name'],null)];

        // for ($i=2; $i <= $unsplashes[0]->total_pages; $i++) { 
        //     $unsplashes[] = UnsplashImages($data['name'],$i);
        // }

        // foreach($unsplashes as $unsplash){

        //     foreach($unsplash->results as $photo){

        //         $images[] = [
        //             'url'=>$photo->urls->regular,
        //             'photographer'=>$photo->user->username,
        //             'photographer_id'=>$photo->user->id
        //         ];

        //     }

        // }

        // $pixabays = [PixabayImages($data['name'],null)];

        // for ($i=2; $i <= ceil($pixabays[0]->totalHits/200); $i++) { 
        //     $pixabays[] = PixabayImages($data['name'],$i);
        // }

        // foreach($pixabays as $pixabay){

        //     foreach($pixabay->hits as $photo){

        //         $images[] = [
        //             'url'=>$photo->webformatURL,
        //             'photographer'=>$photo->user,
        //             'photographer_id'=>$photo->user_id
        //         ];

        //     }

        // }


        $pexels = [PexelImages($data['name'],null)];

        // for ($i=2; $i <= ceil($pexels[0]->total_results/$pexels[0]->per_page); $i++) { 
        //     $pexels[] = PexelImages($data['name'],$i);
        // }

        foreach($pexels as $pexel){

            foreach($pexel->photos as $photo){

                $images[] = [
                    'url'=>$photo->src->large2x,
                    'photographer'=>$photo->photographer,
                    'photographer_id'=>$photo->photographer_id
                ];

            }

        }

        return view('sandbox.image',['images'=>$images,'data'=>$data]);

    }


    public function image_faq_post(){

        set_time_limit(0);
        
        $faq = ArticleFaq::where('id',33)->first();

        $data = [
            "undertitle"=>"",
            "name"=>'monstera',
            "q" => $faq->question,
            "a" => ltrim(AiGenerateText('Write a sentence answer for'.$faq->question,['temperature'=>1]))
        ];

        $images = [];


        $pexels = [PexelImages($data['name'],null)];

        // for ($i=2; $i <= ceil($pexels[0]->total_results/$pexels[0]->per_page); $i++) { 
        //     $pexels[] = PexelImages($data['name'],$i);
        // }

        foreach($pexels as $pexel){

            foreach($pexel->photos as $photo){

                $images[] = [
                    'url'=>$photo->src->large2x,
                    'photographer'=>$photo->photographer,
                    'photographer_id'=>$photo->photographer_id
                ];

            }

        }

        return view('sandbox.image_question',['images'=>$images,'data'=>$data]);

    }


    public function image_picker(Request $req){

        $species = Species::where('id',$req->id)->first();

        if ($req->key != env('SITE_SECRET_ADMIN_KEY_1')) {
            return 'invalid key';
        }

        if ($species->id >= $req->from && $species->id <= $req->to) {

        }else{
            return 'surpassed';
        }

        return view('sandbox.image_picker',['species'=>$species,'request'=>$req->query()]);

    }

    public  function image_picker_result(Request $req){

        if ($req->key != env('SITE_SECRET_ADMIN_KEY_1')) {
            return 'invalid key';
        }

        $species = Species::whereBetween('id', [$req->from,$req->to])->get();

        $missing = Species::whereBetween('id', [$req->from,$req->to])->whereIn('image',[null,'[]'])->get();

        return view('sandbox.image_picker_result',['species'=>$species,'missing'=>$missing,'request'=>$req->query()]);

    }

    public  function disease_picker(Request $req){

        $species = SpeciesIssue::where('id',$req->id)->first();

        if ($req->key != env('SITE_SECRET_ADMIN_KEY_1')) {
            return 'invalid key';
        }

        if ($species->id >= $req->from && $species->id <= $req->to) {

        }else{
            return 'surpassed';
        }

        return view('sandbox.disease_picker',['species'=>$species,'request'=>$req->query()]);

    }

    function image_posting(Request $request){
        // return 123;
        $path = $request->old;
        return($request);
        $path = str_replace('url("', '', $path);
        $path = str_replace('")', '', $path);
        $contents = file_get_contents($path);
        $filepath = 'convertedfiles.png';
        $path = Storage::disk('public')->put($filepath, $contents);
        return 'url("'.asset('storage/'.$filepath).'")';
    }


    function google_cleanup(){

        $merchants = GoogleMerchant::where('website','like','%brightpath%')
        ->orWhere('website','like','%school%')
        ->orWhere('website','like','%hobbylobby%')
        ->orWhere('website','like','%sunshinehouse%')
        ->orWhere('website','like','%weecare%')
        ->orWhere('website','like','%nps%')
        ->orWhere('website','like','%.gov%')
        ->orWhere('website','like','%children%')
        ->orWhere('website','like','%stihldealer%')
        ->orWhere('website','like','%nurserykid%')
        ->orWhere('website','like','%cvs%')
        ->orWhere('website','like','%people-and%')
        ->orWhere('website','like','%superstore%')
        ->orWhere('website','like','%dollar%')
        ->orWhere('website','like','%bluediamond%')
        ->orWhere('website','like','%jewelry%')
        ->orWhere('website','like','%jewellery%')
        ->orWhere('website','like','%academy%')
        ->orWhere('website','like','%daycare%')
        ->orWhere('website','like','%child%')
        ->orWhere('website','like','%walmart%')
        ->orWhere('website','like','%target%')
        ->orWhere('website','like','%learningexperience%')
        ->orWhere('website','like','%reports%')
        ->orWhere('website','like','%hardware%')
        ->orWhere('website','like','%sportchek%')
        ->orWhere('website','like','%sportcheck%')
        ->orWhere('website','like','%champs%')
        ->orWhere('website','like','%shoes%')
        ->orWhere('website','like','%truevalue%')
        ->orWhere('website','like','%tutor%')
        ->orWhere('website','like','%clement%')
        ->orWhere('website','like','%kidsplant%')
        ->orWhere('website','like','%hy-vee%')
        ->orWhere('website','like','%footlocker%')
        ->orWhere('website','like','%lowes%')
        ->orWhere('website','like','%kindercare%')
        ->orWhere('website','like','%siteone%')
        ->orWhere('website','like','%childcare%')
        ->orWhere('website','like','%brighthorizon%')
        ->orWhere('website','like','%fredmeyer%')
        ->orWhere('website','like','%walgreen%')
        ->orWhere('website','like','%dollargeneral%')
        ->orWhere('website','like','%canadiantire%')
        ->orWhere('website','like','%rona%')
        ->orWhere('website','like','%homedepot%')
        ->orWhere('website','like','%softmac%')
        ->orWhere('website','like','%cible%')
        ->orWhere('website','like','%christian%')
        ->orWhere('website','like','%irrigation%')
        ->orWhere('website','like','%costco%')
        ->orWhere('website','like','%londondrug%')
        ->orWhere('website','like','%wholefood%')
        ->get();



    }


}
