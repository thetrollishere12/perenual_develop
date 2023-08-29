<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Species;
use App\Models\Survey;
use Redirect;

class SurveyController extends Controller
{
    public function index(){

        return view('survey.index');

    }

    public function find_my_houseplant(){

        return view('survey.general.survey',[
            'title'=>'Find My Houseplant Match Test',
            'description'=>'Take a free survey to help discover the perfect houseplant suitable for you. This online quiz helps you find, identify and suggest the best plant for you',
            'survey_input'=>[
                'h1'=>'Help Find My Perfect House Plant',
                'p'=>"Take a brief 1-minute assessment to tell us about yourself and enable us to identify the ideal house plant that matches your preferences",
                'components'=>[
                  'CareLevel',
                  'PoisonousToPets',
                  'Watering',
                  'Sunlight'
                ],
                'fill'=>[
                  'indoor'=>true
                ],
                'set_images'=>[
                  // 'watering'=>'Rare'
                ]
            ]
        ]);


//         return view('survey.general.start-default',[
//             'title'=>'',
//             'description'=>'',
//             'h1'=>'Houseplant Test - Find My Perfect Indoor Plant',
//             'p'=>"
// This page is designed to help you find the perfect plants for your home, office, or garden. Whether you're looking for a low-maintenance plant for the beginner gardener, a statement plant for your living room, or a drought-tolerant plant for your patio, we have the information you need. Our easy-to-follow guides will help you decide which plants are best for your space, and our expert tips will ensure your plants thrive.",
//             'image'=>'',
//             'image-alt'=>'',
//             'link'=>'plant-survey-quiz-test/find-my-houseplant'
//         ]);

    }


    public function find_my_plant(){

        return view('survey.general.survey',[
            'title'=>'Find My Plant Match Test',
            'description'=>'Take a free survey to help discover the perfect plant suitable for you. This online quiz helps you find, identify and suggest the best plant for you',
            'survey_input'=>[
                'h1'=>'Help Find My Perfect Plant',
                'p'=>"Take a brief 1-minute assessment to tell us about yourself and enable us to identify the ideal plant that matches your preferences",
                'components'=>[
                  'CareLevel',
                  'Watering',
                  'Sunlight',
                  'Flower',
                  'Fruits'
                ],
                'fill'=>[
                ],
                'set_images'=>[
                ]
            ]
        ]);

    }

    public function find_my_outdoor_plant(){

        return view('survey.general.survey',[
            'title'=>'Find My Outdoor Plant Match Test',
            'description'=>'Take a free survey to help discover the perfect outdoor plant suitable for you. This online quiz helps you find, identify and suggest the best plant for you',
            'survey_input'=>[
                'h1'=>'Help Find My Perfect Outdoor Plant',
                'p'=>"Take a brief 1-minute assessment to tell us about yourself and enable us to identify the ideal plant that matches your preferences",
                'components'=>[
                  'CareLevel',
                  'Watering',
                  'Sunlight',
                  'Flower',
                  'Fruits',
                  'Cycle'
                ],
                'fill'=>[
                  'indoor'=>false
                ],
                'set_images'=>[
                  // 'watering'=>'Rare'
                ]
            ]
        ]);

    }

    public function find_my_garden_plant(){

        return view('survey.general.survey',[
            'title'=>'Find My Garden Plant Match Test',
            'description'=>'Take a free survey to help discover the perfect garden plant suitable for you. This online quiz helps you find, identify and suggest the best plant for you',
            'survey_input'=>[
                'h1'=>'Help Find My Perfect Garden Plant',
                'p'=>"Take a brief 1-minute assessment to tell us about yourself and enable us to identify the ideal plant that matches your preferences",
                'components'=>[
                  'CareLevel',
                  'Watering',
                  'Sunlight',
                  'Flower',
                  'Fruits',
                  'Cycle'
                ],
                'fill'=>[
                  'indoor'=>true
                ],
                'set_images'=>[
                  // 'watering'=>'Rare'
                ]
            ]
        ]);

    }


    public function find_my_fruit_plant(){

        return view('survey.general.survey',[
            'title'=>'Find My Fruit Plant Match Test',
            'description'=>'Take a free survey to help discover the perfect fruit plant suitable for you. This online quiz helps you find, identify and suggest the best plant for you',
            'survey_input'=>[
                'h1'=>'Help Find My Perfect Fruit Plant',
                'p'=>"Take a brief 1-minute assessment to tell us about yourself and enable us to identify the ideal plant that matches your preferences",
                'components'=>[
                  'CareLevel',
                  'Watering',
                  'Sunlight',
                  'Cycle'
                ],
                'fill'=>[
                  'fruits'=>true
                ],
                'set_images'=>[
                  // 'watering'=>'Rare'
                ]
            ]
        ]);

    }


    public function find_my_flower_plant(){

        return view('survey.general.survey',[
            'title'=>'Find My Flower Plant Match Test',
            'description'=>'Take a free survey to help discover the perfect flower plant suitable for you. This online quiz helps you find, identify and suggest the best plant for you',
            'survey_input'=>[
                'h1'=>'Help Find My Perfect Flower Plant',
                'p'=>"Take a brief 1-minute assessment to tell us about yourself and enable us to identify the ideal plant that matches your preferences",
                'components'=>[
                  'CareLevel',
                  'Watering',
                  'Sunlight',
                  'Cycle'
                ],
                'fill'=>[
                  'flowers'=>true
                ],
                'set_images'=>[
                  'start'=>'pexels-thierry-fillieul-1046495'
                ]
            ]
        ]);

    }


    public function result(){

        $analyze = session()->get('analyze');

        if ($analyze) {

        $data = $analyze->data;

        $species=Species::when(isset($data['indoor']),function($q) use($data){
            $q->where('indoor',$data['indoor']);
        })
        ->when(isset($data['care_level']) && $data['care_level'] != "null",function($q) use($data){
            $q->where('care_level',$data['care_level']);
        })
        ->when(isset($data['cuisine']) && $data['cuisine'] != "null",function($q) use($data){
            $q->where('cuisine',$data['cuisine']);
        })
        ->when(isset($data['cycle']) && $data['cycle'] != "null",function($q) use($data){
            $q->where('cycle',$data['cycle']);
        })
        ->when(isset($data['edible']) && $data['edible'] != "null",function($q) use($data){
            $q->where('edible',$data['edible']);
        })
        ->when(isset($data['flower']) && $data['flower'] != "null",function($q) use($data){
            $q->where('flowers',$data['flower']);
        })
        ->when(isset($data['fruits']) && $data['fruits'] != "null",function($q) use($data){
            $q->where('fruits',$data['fruits']);
        })
        ->when(isset($data['maintenance']) && $data['maintenance'] != "null",function($q) use($data){
            $q->where('maintenance',$data['maintenance']);
        })
        ->when(isset($data['medicinal']) && $data['medicinal'] != "null",function($q) use($data){
            $q->where('medicinal',$data['medicinal']);
        })
        ->when(isset($data['rare']) && $data['rare'] != "null",function($q) use($data){
            $q->where('rare',$data['rare']);
        })
        ->when(isset($data['sunlight']) && $data['sunlight'] != "null",function($q) use($data){
            $q->where('sunlight','LIKE','%'.$data['sunlight'].'%');
        })
        ->when(isset($data['thorny']) && $data['thorny'] != "null",function($q) use($data){
            $q->where('thorny',$data['thorny']);
        })
        ->when(isset($data['tropical']) && $data['tropical'] != "null",function($q) use($data){
            $q->where('tropical',$data['tropical']);
        })
        ->when(isset($data['watering']) && $data['watering'] != "null",function($q) use($data){
            $q->where('watering',$data['watering']);
        })
        ->whereNot('default_image',null)
        ->paginate(30);


        
        Survey::find($analyze->id)->update([
            'result'=>$species->total()
        ]);

        return view('survey.general.result',[
            'queries'=>$species
        ]);

        }else{
            return Redirect::back();
        }

    }

}
