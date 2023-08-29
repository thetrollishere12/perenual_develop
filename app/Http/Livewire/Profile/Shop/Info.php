<?php

namespace App\Http\Livewire\Profile\Shop;

use Livewire\Component;
use Storage;
use App\Models\Store;
use Auth;
use App\Models\StoreElement;
use App\Models\StoreSocialMedia;
use App\Models\ShopAddress;
use Illuminate\Support\Str;

use WireUi\Traits\Actions;
use Livewire\WithFileUploads;

use Carbon\Carbon;

class Info extends Component
{
    use Actions;
    use WithFileUploads;


    public $redirect;

    public $local_pickup;
    public $about;
    public $name;
    public $return_exchange = "30 days return/exchange";
    public $return_exchange_policy;
    public $country;
    public $social = [];

    public $line1;
    public $line2;
    public $city;
    public $state_county_province_region;
    public $postal_zip;

    public $image;
    public $temp_image;
    public $import_image;

    public function updated($fields)
    {   
        $this->validateOnly($fields,[
            'temp_image' => 'image|max:1024',
        ]);

    }
    
    public function mount(){

        $this->json = json_decode(Storage::disk('local')->get('json/country.json'), true);
        $this->store = get_store_with_element()->first();
        $this->mediaTypes = json_decode(Storage::disk('local')->get('json/social_media.json'), true); 

        $this->state_county_province_region = $this->json[0]['states'][0];
        
        if($this->store){
            $this->store = $this->store->toArray();
            $this->image = $this->store['profile_photo_path'];
            $this->name = $this->store['name'];
            $this->local_pickup = $this->store['local_pickup'];
            $this->about = $this->store['about'];
            $this->return_exchange = $this->store['return_exchange'];
            $this->return_exchange_policy = $this->store['return_exchange_policy'];
            $this->country = $this->store['country'];

            if ($this->store['local_pickup'] == true) {
                $address = ShopAddress::where('store_id',$this->store['store_id'])->first();
                if ($address) {
                    $address = $address->toArray();
                    $this->line1 = $address['line1'];
                    $this->line2 = $address['line2'];
                    $this->city = $address['city'];
                    $this->state_county_province_region = $address['state_county_province_region'];
                    $this->postal_zip = $address['postal_zip'];
                }
            }

            $socialMedia = get_social_media();
            foreach($socialMedia as $media){
                $this->social[$media->platform] = $media->url;
            }

        }elseif(!$this->store && $etsy = Auth::user()->connected_etsy()->first()){
            $store = etsy_get_store($etsy->shop_id);

            $random = random_id('etsy-');

            // Move to new temporary folder 
            $this->import_image = $store->icon_url_fullxfull;

            $this->name = $store->shop_name;
            $this->about = $store->title;
            $this->country = $store->shipping_from_country_iso;
            
        }

    }

    public function check(){
        
        $this->resetErrorBag('name');

        $store = get_store()->first();

        $count = Store::where('name',$this->name)->where('id','!=',($store)?$store->id:null)->get()->count();

        if ($count > 0) {
            $this->addError('name', 'Store name already exist. Try another one');
        }

    }

    public function local_pickup(){

        if ($this->country) {
            
            foreach($this->json as $country){
                if ($country['code'] == $this->country) {
                    $this->state_county_province_region = $country['states'][0];
                }
            }

        }elseif($this->local_pickup == true && !$this->country){
            $this->local_pickup = false;
            $this->addError('country', 'Please select where your store is location');
        }

    }

    public function submit(){

        $this->validate([
            'name' => 'required|string|min:5|max:100',
            'about' => 'string|max:10000|nullable',
            'return_exchange'=>'required',
            'return_exchange_policy' => 'max:2000',
            'country' => 'required|max:100',
            'social' =>'array'
        ]);

        if ($this->local_pickup) {
            $this->validate([
                'line1' => 'required|string|max:100',
                'line2' => 'nullable|string|max:100',
                'state_county_province_region' => 'required',
                'city' => 'required|max:100',
                'postal_zip' => 'required|max:100'
            ]);
        }

        try {

        $count = Store::where('name',$this->name)->where('user_id','!=',Auth::id())->get()->count();

        if ($count > 0) {
            return $this->addError('name', 'Store name already exist. Try another one');
        }


        // Store
        if ($this->temp_image) {
            $this->image = Carbon::now()->timestamp. '.' .$this->temp_image->extension();
        }
        
        if ($this->import_image) {
            $this->image = Carbon::now()->timestamp. '.jpg';
        }

        $this->store = Store::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'profile_photo_path'=>$this->image,
                'name'=>$this->name,
                'currency'=>country_code_to_currency($this->country),
                'country'=>$this->country,
                'local_pickup'=>$this->local_pickup,
            ]
        );

        if($this->temp_image){
            $this->temp_image->storeAs('shop-profile-photos/'.$this->store->id, $this->image);
        }

        if ($this->import_image) {
            Storage::disk('public')->put('shop-profile-photos/'.$this->store->id.'/'.$this->image,file_get_contents($this->import_image,true));
        }

        // Store Details
        StoreElement::updateOrCreate(
            ['store_id' => $this->store->id],
            [
                'about'=>$this->about,
                'return_exchange'=>$this->return_exchange,
                'return_exchange_policy'=>$this->return_exchange_policy,
            ]
        );

        // Add bank account for shop
        create_bank_balance(null);

        if ($this->local_pickup) {

            ShopAddress::updateOrCreate(
                ['store_id' => $this->store->id],
                [
                    'line1'=>$this->line1,
                    'line2'=>$this->line2,
                    'postal_zip'=>$this->postal_zip,
                    'state_county_province_region'=>$this->state_county_province_region,
                    'country'=>$this->country,
                    'city'=>$this->city,
                ]
            );

        }

        foreach ($this->social as $k => $key) {
   
            foreach($this->mediaTypes as $type){

                if(str_contains(Str::lower($key),$type['platform']) == true){

                    $exist = StoreSocialMedia::where('store_id',$this->store->id)->where('platform',$type['platform'])->get();

                     if (count($exist) == 0) {
                        
                        $media = new StoreSocialMedia;
                        $media->store_id = $this->store->id;
                        $media->platform = $type['platform'];
                        $media->url = Str::lower($key);
                        $media->save();

                    }else{

                        StoreSocialMedia::where('store_id',$this->store->id)->where('platform',$type['platform'])->update([
                            'url'=>Str::lower($key)
                        ]);

                    }

                }

            }

            if($key == ""){
                StoreSocialMedia::where('store_id',$this->store->id)->where('platform',$k)->delete();
            }

        }

        $this->notification([
            'title'       => 'Saved!',
            'description' => 'Your shop profile was successfully saved '.(($this->redirect) ? 'and now will be redirected' : ''),
            'icon'        => 'success',
        ]);

        if ($this->redirect) {
            return Redirect($this->redirect);
        }

        } catch (\Exception $e) {
            return $this->addError('error', $e->getMessage());
        }


    }

    public function render()
    {

        if ($this->country) {

            foreach($this->json as $key => $country){
                if ($country['code'] == $this->country) {
                    $this->spr = $country;
                }
            }
        }

        return view('livewire.profile.shop.info');
    }
}
