<?php

namespace App\Http\Livewire\Shopping\Checkout;

use Livewire\Component;
use Storage;

class BillingAddress extends Component
{

    public $type;
    public $billing = "";

    public $country_code;
    public $line1;
    public $line2;
    public $city;
    public $state_county_province_region;
    public $postal_zip;
    public $hasShipping;

    public function mount(){
        $this->json = json_decode(Storage::disk('local')->get('json/country.json'), true);

        $this->country_code = $this->json[0]['code'];
        $this->state_county_province_region = $this->json[0]['states'][0];

    }

    public function render()
    {

        if ($this->country_code) {

            foreach($this->json as $key => $country){
                if ($country['code'] == $this->country_code) {
                    $this->spr = $country;
                }
            }
        }

        $this->hasShipping = has_shipping();

        return view('livewire.shopping.checkout.billing-address');
    }
}
