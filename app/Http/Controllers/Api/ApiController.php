<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

use App\Models\Species;
use App\Models\SpeciesImage;
use App\Models\ArticleFaq;
use App\Http\Resources\Species\SpeciesListCollection;
use App\Http\Resources\Species\SpeciesResource;


use App\Http\Resources\ArticleFaq\ArticleFaqResource;
use App\Http\Resources\ArticleFaq\ArticleFaqCollection;

use App\Http\Resources\Disease\DiseaseListCollection;

use App\Models\SpeciesIssue;


use App\Models\SpeciesCareGuide;
use App\Models\SpeciesCareGuideSection;

use App\Http\Resources\SpeciesGuide\SpeciesGuideResource;
use App\Http\Resources\SpeciesGuide\SpeciesGuideCollection;

use App\Helper\EncoderHelper;
use Carbon\Carbon;

use App\Models\ApiCallLog;

class ApiController extends Controller
{
    
    private $limit = 3000;

    private static function api_log($user_id,$key,$url){

        ApiCallLog::create([
            'user_id'=>$user_id,
            'api_key'=>$key,
            'request_uri'=>$url
        ]);

    }

    public function species_list(Request $req){
        
        $check = api_key_check($req->key);

        $this->api_log($check->user_id,$req->key,$req->getRequestUri());

        $subscription = is_subscribed_type($check->user_id,'subscription');
        
        $req->limit = $this->limit;
        $req->subscription = $subscription->count();

        $species = Species::when($req->q,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('common_name','LIKE','%'.$req->q.'%')
                ->orWhere('scientific_name','LIKE','%'.$req->q.'%')
                ->orWhere('other_name','LIKE','%'.$req->q.'%');
            });
        })

        ->when($req->order,function($q) use($req){
            if (in_array($req->order, ['asc', 'desc'])) {
                $q->orderBy('common_name', $req->order);
            }
        }, function ($q) {
            $q->orderBy('id');
        })

        ->when($req->edible,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('edible_fruit',$req->edible)
                ->orWhere('edible_leaf',$req->edible);
            });
        })
        ->when($req->indoor,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('indoor',$req->indoor);
            });
        })
        ->when($req->poisonous,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('poisonous_to_humans',$req->poisonous)
                ->orWhere('poisonous_to_pets',$req->poisonous);
            });
        })
        ->when($req->cycle,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('cycle','LIKE','%'.$req->cycle.'%');
            });
        })
        ->when($req->watering,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('watering','LIKE','%'.$req->watering.'%');
            });
        })
        ->when($req->sunlight,function($q) use($req){
            $q->where(function($query) use($req){

                $sunlight = str_replace('_', ' ', $req->sunlight);

                $query->orwhere('sunlight','LIKE','%'.$sunlight.'%');

                // $sunlight = explode(',',str_replace('_', ' ', $req->sunlight));

                // for ($i=0; $i < count($sunlight); $i++) { 
                //     $query->orwhere('sunlight','LIKE','%'.$sunlight[$i].'%');
                // }
                
            });
        })
        ->when($req->hardiness,function($q) use($req){

            $hardiness = explode("-",$req->hardiness);

            if (count($hardiness) == 1) {
                $hardiness[1] = $hardiness[0];
            }
        
            $q->where(function($query) use($hardiness){
                $query->where('hardiness->min','REGEXP','[[:<:]](' . implode('|', range((int)$hardiness[0], (int)$hardiness[1])) . ')([A-Za-z]?)')
                ->where('hardiness->max','REGEXP','[[:<:]](' . implode('|', range((int)$hardiness[0], (int)$hardiness[1])) . ')([A-Za-z]?)');
            });

        })
        ->when(!$req->subscription && $req->compress,function($q){
            $q->whereBetween('id',[1,$this->limit]);
        })
        ->orderBy('created_at', 'ASC')
        ->paginate(30);

        return new SpeciesListCollection($species);
    }

    public function species_details(Request $req,$id){

        $check = api_key_check($req->key);

        $this->api_log($check->user_id,$req->key,$req->getRequestUri());

        $req->subscription = is_subscribed_type($check->user_id,'subscription');
        
        if (!$req->subscription->count() && $id > $this->limit) {
            return response('Please Upgrade Plan - '.url("subscription-api-pricing").'. Sorry😬', 429);
        }else{
            return new SpeciesResource(Species::findOrFail($id));
        }

    }

    // public function species_images(Request $req,$id){

    //     $check = api_key_check($req->key);

    //     if (is_subscribed($check->user_id)->count() == 0) {
    //         return response()->json(['message' => 'Please Upgrade Plan For Access. Im sorry.'],402);
    //     }

    //     $species = Species::find($id);

    //     $images = Storage::disk('public')->allFiles('species_image/'.$species->folder.'/og/');

    //     $list = [];

    //     foreach($images as $image){

    //         $si = SpeciesImage::where('name',basename($image))->first();

    //         $list[] = [
    //             'url'=>Storage::disk('public')->url($image),
    //             'license'=>$si->license,
    //             'license_name'=>$si->license_name,
    //             'license_url'=>$si->license_url
    //         ];

    //     }

    //     return $list;

    // }


    public function disease_list(Request $req){

        $check = api_key_check($req->key);

        $this->api_log($check->user_id,$req->key,$req->getRequestUri());

        $subscription = is_subscribed_type($check->user_id,'subscription');

        $req->subscription = $subscription->count();

        $species = SpeciesIssue::when(!$req->subscription,function($q){
            $q->whereBetween('id',[1,100]);
        })
        ->when($req->q,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('common_name','LIKE','%'.$req->q.'%')
                ->orWhere('scientific_name','LIKE','%'.$req->q.'%')
                ->orWhere('other_name','LIKE','%'.$req->q.'%')
                ->orWhere('description','LIKE','%'.$req->q.'%')
                ->orWhere('solution','LIKE','%'.$req->q.'%')
                ->orWhere('host','LIKE','%'.$req->q.'%');
            });
        })
        ->when($req->type,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('type',$req->id);
            });
        })
        ->when($req->id,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('id',$req->id);
            });
        })
        ->orderBy('created_at', 'ASC')
        ->paginate(30);

        return new DiseaseListCollection($species);


    }


    // Supreme API

    public function article_faq_list(Request $req){

        $check = api_key_check($req->key);
        
        $this->api_log($check->user_id,$req->key,$req->getRequestUri());

        $subscription = is_subscribed_to($check->user_id,'supreme api');

        if ($subscription->count() <= 0 && $req->id > $this->limit) {
            return response('Please Upgrade Plan To Supreme - '.url("subscription-api-pricing").'. Sorry😬', 429);
        }

        $species = ArticleFaq::when($req->q,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('question','LIKE','%'.$req->q.'%')
                ->orWhere('answer','LIKE','%'.$req->q.'%')
                ->orWhere('tags','LIKE','%'.$req->q.'%');
            });
        })

        ->when($subscription->count() <= 0,function($q){
            $q->whereBetween('id',[1,$this->limit]);
        })

        ->when($req->id,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('id',$req->id);
            });
        })
        ->orderBy('created_at', 'ASC')
        ->paginate(30);

        return new ArticleFaqCollection($species);

    }


    public function species_care_guide_list(Request $req){

        $check = api_key_check($req->key);

        $this->api_log($check->user_id,$req->key,$req->getRequestUri());

        $subscription = is_subscribed_to($check->user_id,'supreme api');
        
        if ($subscription->count() <= 0 && $req->species_id > $this->limit) {
            return response('Please Upgrade Plan To Supreme - '.url("subscription-api-pricing").'. Sorry😬', 429);
        }

        $species = SpeciesCareGuide::when($req->q,function($q) use($req){
            $q->where(function($query) use($req){
                $query->orwhere('common_name','LIKE','%'.$req->q.'%')
                ->orWhere('scientific_name','LIKE','%'.$req->q.'%');
            });
        })

        ->when($subscription->count() <= 0,function($q){
            $q->whereBetween('species_id',[1,$this->limit]);
        })

        ->when($req->species_id,function($q) use($req){

            $species = Species::findOrFail($req->species_id);

            $q->where(function($query) use($species){
                $query->orWhere('species_id',$species->id);
            });

        })
        ->orderBy('created_at', 'ASC')
        ->paginate(30);

        return new SpeciesGuideCollection($species);

    }


    public function hardiness_map(Request $req){

        try{

            $check = api_key_check($req->key);

            $this->api_log($check->user_id,$req->key,$req->getRequestUri());
            
            $subscription = is_subscribed_to($check->user_id,'supreme api');

            if ($subscription->count() <= 0 && $req->species_id > $this->limit) {
                return response('Please Upgrade Plan To Supreme - '.url("subscription-api-pricing").'. Sorry😬', 429);
            }

            return view('api.hardiness-map.index');

        }catch(\Exception $e){
            abort(404);
        }

    }

    public function hardiness_map_sample(){

        return "<img src='".Storage::disk('public')->url('image/hardiness/hardiness_map.png')."'>";

    }


}
