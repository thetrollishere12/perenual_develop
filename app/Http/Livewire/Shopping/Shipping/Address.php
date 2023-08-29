<?php

namespace App\Http\Livewire\Shopping\Shipping;

use Livewire\Component;
use Storage;
use App\Models\Address as Addresses;
use Auth;
use App\Models\ShippingDomestic;
use App\Models\ShippingInternational;


class Address extends Component
{

    public $subtotal;
    public $discount;

    public $name;
    public $email;
    public $country;
    public $country_code;
    public $line1;
    public $line2;
    public $city;
    public $state_county_province_region;
    public $postal_zip;
    public $shippingMethod="";
    public $hasShipping = false;

    protected $listeners = ['mount','address-updated'=>'render'];

    public function mount(){

        $this->json = json_decode(Storage::disk('local')->get('json/country.json'), true);

        $this->country_code = $this->json[0]['code'];
        $this->state_county_province_region = $this->json[0]['states'][0];

        $user = Auth::user() ?: session('cart.user');
        $this->name = $user->name ?? session('cart.user.contact_name');
        $this->email = $user->email ?? session('cart.user.email_address');
        $this->phone = session('cart.user.phone');

        if (session('cart.shipping.address')) {
            $this->line1 = session('cart.shipping.address.line1');
            $this->line2 = session('cart.shipping.address.line2');
            $this->city = session('cart.shipping.address.city');
            $this->country = session('cart.shipping.address.country');
            $this->country_code = session('cart.shipping.address.country_code');
            $this->state_county_province_region = session('cart.shipping.address.spr');
            $this->postal_zip = session('cart.shipping.address.zipcode');
        }

    }

    public function select($id){

        if ($id != null) {
            
            $address = Addresses::where('user_id',Auth::user()->id)->where('id',$id)->first();
            // Add values into session
            session()->put('cart.user',[
                "contact_name"=>$address->name,
                "email_address"=>Auth::user()->email,
                "phone"=>(isset($address->phone)) ? $address->phone : null,
            ]);


            session()->put('cart.shipping.address',[
                "line1"=>$address->line1,
                "line2"=>$address->line2,
                "country"=>country_code_to_string($address->country),
                "country_code"=>$address->country,
                "spr"=>$address->state_county_province_region,
                "city"=>$address->city,
                "zipcode"=>$address->postal_zip,
            ]);

            $this->emit('mount');

        }

    }

    public function country(){

        foreach($this->json as $country){
            if ($country['code'] == $this->country_code) {
                $this->state_county_province_region = $country['states'][0];
            }
        }

    }

    public function submit(){

        $this->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'phone'=>'string|nullable'
        ]);

        session()->put('cart.user',[
            "contact_name"=>$this->name,
            "email_address"=>$this->email,
            "phone"=>(isset($this->phone)) ? $this->phone : null,
        ]);



        // Add values into session
        session()->put('cart.shipping.address',[
            "line1"=>$this->line1,
            "line2"=>$this->line2,
            "country"=>country_code_to_string($this->country_code),
            "country_code"=>$this->country_code,
            "spr"=>$this->state_county_province_region,
            "city"=>$this->city,
            "zipcode"=>$this->postal_zip,
        ]);

        return Redirect('checkout');

    }

    public function render()
    {

        $this->addresses = collect();
        if(Auth::user()){
            $this->addresses = Addresses::where('user_id',Auth::user()->id)->orderBy('default','desc')->get();
        }

        if ($this->country_code) {

            session()->put('cart.shipping.address.country_code',$this->country_code);

            foreach($this->json as $key => $country){

                if ($country['code'] == $this->country_code) {
                    $this->spr = $country;
                }
            }

            foreach (session('cart.shopping_cart') as $sku => $cart) {
                
                foreach ($cart['list'] as $l => $details){

                    $shipping = ShippingDomestic::where('id',$details['product']->shippingMethod)->whereIn('origin',[$this->country_code,'Everywhere'])->get();

                    if ($shipping->count() == 0) {
                        
                        $shipping = ShippingInternational::where('shipping_id',$details['product']->shippingMethod)->whereIn('origin',[$this->country_code,'Everywhere'])->get()->sortBy('cost');

                        if ($shipping->count() == 0) {
                            
                            $this->addError('shipping', 'Product '.strtoupper($details['product']->sku).' does not ship to '.country_code_to_string($this->country_code).'. Please remove it');

                        }
                    }

                }

            }

        }

        $this->subtotal = subtotal();
        
        if (session('cart.coupon_code_applied.discount')) {
            $this->discount = session('cart.coupon_code_applied.discount');
        }
        
        $this->hasShipping = has_shipping();


        return view('livewire.shopping.shipping.address');
    }
}
