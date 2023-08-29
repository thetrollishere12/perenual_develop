<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Socialite;
use DB;
use Auth;

use Carbon\Carbon;

use App\Models\Species;
use App\Models\UniqueVisitor;
use App\Models\SpeciesCareGuide;
use App\Models\SpeciesCareGuideSection;

class AdminController extends Controller
{

    public function panel(){

        $roles = DB::table('roles')->get()->unique('slug');

        $employee = [];

        foreach ($roles as $key => $role) {

            $employee[$role->slug] = User::whereHas('roles', function($q) use($role){
                $q->where('name',$role->slug);
            })->get();         
        }

        return view('admin.admin_panel',['roles'=>$employee]);

    }

    public function export_user(Request $req){
        return view('admin.user.user',['req'=>$req->query()]);
    }

    public function instagram_messaging(Request $req){

        return view('admin.marketing.instagram.comment',['req'=>$req->query()]);

    }


    public function merchant_send_email(Request $req){

        return view('admin.merchant.send-email');

    }


    public function merchant_send_bulk_email(Request $req){
        return view('admin.marketing.email.email-sender');
    }


    public function redirect(Request $req){

        if (isset($req->link)) {
            session()->put('redirect_link',$req->link);
        }

        return Socialite::driver('facebook')->redirect();

    }



    public function success(){

        dd(Socialite::driver('facebook')->user());

        try {
            $platformUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect('/register');
        }

        $existUser = User::where('email', $platformUser->email)->first();

        if ($existUser) {

            if ($existUser->google_id) {
                Auth::loginUsingId($existUser->id);
            }else{

                return redirect()->intended('google-link')->with(['id'=>$platformUser->id,'user'=>$existUser->email]);

            }

        }else{
            $user = new User;
            $user->name = $platformUser->name;
            $user->email = $platformUser->email;
            $user->google_id = $platformUser->id;
            $user->email_verified_at = Carbon::now()->toDateTimeString();
            $user->password = bcrypt(request('password'));
            $user->save();

            //every user needs a team for dashboard/jetstream to work.
            //create a personal team for the user
  
            $newTeam = Team::forceCreate([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0]."'s Team",
                'personal_team' => true,
            ]);
            // save the team and add the team to the user.
            $newTeam->save();
            $user->current_team_id = $newTeam->id;
            $user->save();

            Auth::loginUsingId($user->id);
        }

        if (session('redirect_link')) {
            return redirect()->intended(session('redirect_link'));
        }else{
            return redirect()->intended('/');
        }

    }




    public function merchant_etsy(){

        return view('admin.merchant.etsy');

    }


    public function merchant_google(){
        return view('admin.merchant.google');
    }



    public function etsy_product_importer($id){

        return view('admin.merchant.importer.etsy.product',['id'=>$id]);

    }


    public function species_nutrition(Request $req){

        $species = Species::where('edible_fruit',true)
        ->orWhere('edible_leaf',true)
        ->orWhere('edible_flower',true)
        ->when($req->q != null,function($q) use($req){
            $q->where('common_name','LIKE','%'.$req->q.'%')
            ->orWhere('scientific_name','LIKE','%'.$req->q.'%')
            ->orWhere('other_name','LIKE','%'.$req->q.'%');
        })
        ->paginate(28);
        
        return view('admin.species.nutrition.index',[
            'queries'=>$species
        ]);

    }


    public function species_nutrition_show($id){

        return view('admin.species.nutrition.show',[
            'id'=>$id
        ]);

    }




    public function species_guide(Request $req){

        $species_of_the_day = UniqueVisitor::where('type','species')
        ->whereDate('created_at','<=',Carbon::today())
        ->whereDate('created_at','>=',Carbon::today()->subDays(15))
        ->select('type_id')->groupBy('type_id')->orderByRaw('COUNT(*) DESC')->limit(280)->pluck('type_id');

        $species = Species::when($req->all != true, function($q) use($species_of_the_day){
            $q->whereIn('id',$species_of_the_day);
        })
        ->when($req->type != null,function($q) use($req){
            $q->where($req->type,true);
        })
        ->when($req->q != null,function($q) use($req){
            $q->where('common_name','LIKE','%'.$req->q.'%')
            ->orWhere('scientific_name','LIKE','%'.$req->q.'%')
            ->orWhere('other_name','LIKE','%'.$req->q.'%');
        })
        ->paginate(28)->appends(request()->query());

        foreach ($species as $key => $queries) {

            if ($queries->guide()->first()) {
                $queries->care_guide = $queries->guide()->first()->section(null)->get()->unique('type');
            }

        }

        return view('admin.species.species-guide.index',[
            'queries'=>$species
        ]);

    }


    public function species_guide_show($id){

        return view('admin.species.species-guide.show',[
            'id'=>$id
        ]);

    }

    public function species_details(){
        return view('admin.species.add-species-details');
    }

    public  function species_guide_current(){

        $species = SpeciesCareGuide::orderBy('created_at', 'desc')->paginate(28)->appends(request()->query());

        foreach ($species as $key => $query) {

            try{
                
            $species[$key] = $query->species()->first();

            $species[$key]->care_guide = $query->section(null)->get()->unique('type');
    
            }catch(\Exception $e){
                unset($species[$key]);
            }

        }

        return view('admin.species.species-guide.finish',[
            'queries'=>$species
        ]);

    }


    public function species_guide_current_user_id($id){

        $uniqueGuideIds = SpeciesCareGuideSection::where('generated_user_id', $id)
    ->get()
    ->pluck('guide_id')
    ->unique()
    ->values();

    $species = SpeciesCareGuide::whereIn('id', $uniqueGuideIds)
        ->paginate(28);
          
        foreach ($species as $key => $query) {

            try{
                
            $species[$key] = $query->species()->first();

            $species[$key]->care_guide = $query->section(null)->get()->unique('type');
    
            }catch(\Exception $e){
                unset($species[$key]);
            }

        }  

        return view('admin.species.species-guide.finish',[
            'queries'=>$species
        ]);


    }


}
