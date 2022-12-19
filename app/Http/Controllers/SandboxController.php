<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class SandboxController extends Controller
{
    
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

    public function test(){
        return view('sandbox.test');
    }

    public function image_post(){

        $data = [
            "undertitle"=>"",
            "name"=>"Aloe Vera",
            "subtitle"=>[
                'Watering.',
                'Sunlight.',
                'Cool Fact.'
            ],
            "description"=>[]
        ];

        foreach($data['subtitle'] as $key => $subtitle){

            $data['description'][$key] = AiGenerateText('Write a paragraph about '.$data['name'].' on '.$data['subtitle'][$key]);

        }


        $images = AiGenerateImg($data['name'].' piccaso style in light gray');
dd($images);
        $images[] = (object)[
            'url'=>'https://oaidalleapiprodscus.blob.core.windows.net/private/org-WRgbihZ9kj08jkGIo69aQxh1/user-LunJZJAJkuM2wff5HI47737w/img-G3WiaMKGxholPmMiRxgpD11U.png?st=2022-12-19T16%3A07%3A30Z&se=2022-12-19T18%3A07%3A30Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2022-12-19T10%3A31%3A07Z&ske=2022-12-20T10%3A31%3A07Z&sks=b&skv=2021-08-06&sig=fOeS4ejC9HJzuRbd1IxeS1rWl4V5HgbxmeEYun/oWng%3D'
        ];

        return view('sandbox.image',['images'=>$images->data,'data'=>$data]);

    }

}
