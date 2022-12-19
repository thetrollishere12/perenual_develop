<?php

namespace App\Http\Livewire\Profile\User;

use Livewire\Component;
use Auth;
use App\Models\Address as Addresses;
use WireUi\Traits\Actions;
use File;

class Address extends Component
{

    public $name;
    public $country;
    public $line1;
    public $line2;
    public $city;
    public $postal_zip;
    public $state_county_province_region;
    public $myModal;
    use Actions;
    protected $listeners = ['refreshComponent' => '$refresh'];
    public function mount(){
        $this->json = json_decode(File::get(public_path('storage/json/country.json')), true);

        $this->country = $this->json[0]['code'];
        $this->state_county_province_region = $this->json[0]['states'][0];

    }

    public function submit(){

        $this->validate([
            'name' => 'required|max:100',
            'line1' => 'required|string|max:100',
            'line2' => 'nullable|string|max:100',
            'country' => 'required|max:100',
            'state_county_province_region' => 'required',
            'city' => 'required|max:100',
            'postal_zip' => 'required|max:100'
        ]);

        try{

        $addresses = Addresses::where('user_id',Auth::user()->id)->get();

        $address = new Addresses;
        $address->user_id = Auth::user()->id;
        $address->name=$this->name;
        $address->city=$this->city;
        $address->country=$this->country;
        $address->line1=$this->line1;
        $address->line2=$this->line2;
        $address->postal_zip=$this->postal_zip;
        $address->state_county_province_region=$this->state_county_province_region;
        if ($addresses->count() == 0) {
            $address->default=true;
        }
        $address->save();

        $this->notification([
            'title'       => 'Successfully Added',
            'description' => 'The shipping address you have provided has been saved',
            'icon'        => 'success',
        ]);

        $this->reset(['line1','line2','city','postal_zip']);

        }catch(\Exception $e){
            $this->notification([
                'title'       => 'There Was An Error',
                'description' => 'The shipping address you have provided could not be saved. Please try again or contact us',
                'icon'        => 'error',
            ]);
        }

        $this->myModal = false;
        $this->emit('refreshComponent');
    }

    public function country(){

        foreach($this->json as $country){
            if ($country['code'] == $this->country) {
                $this->state_county_province_region = $country['states'][0];
            }
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

        $this->addresses = Addresses::where('user_id',Auth::user()->id)->get();

        return view('livewire.profile.user.address');
    }
}
