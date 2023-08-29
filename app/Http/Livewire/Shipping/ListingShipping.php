<?php

namespace App\Http\Livewire\Shipping;

use Livewire\Component;
use App\Models\Product;
use App\Models\ShippingDomestic;
use App\Models\ShippingInternational;
use Storage;
use WireUi\Traits\Actions;
class ListingShipping extends Component
{
    use Actions;
    public $addModalFormVisable = false;

    public $name;
    public $processing;
    public $domestic_free_shipping = false;
    public $domestic_shipping_cost = 0.00;
    public $domestic_additional_shipping_cost = 0.00;
    public $domestic_delivery_from;
    public $domestic_delivery_to;

    public $international = [];

    protected $listeners = ['add_shipping'];

    protected $rules = [
        'name' => 'string|nullable',
        'processing' => 'required|string',
        'domestic_shipping_cost' => 'numeric|nullable',
        'domestic_additional_shipping_cost' => 'numeric|nullable',
        'domestic_delivery_from' => 'required|numeric',
        'domestic_delivery_to' => 'required|numeric'
    ];

    public function mount()
    {
        $store = get_store()->first();
        $this->countries = json_decode(Storage::disk('local')->get('json/backup.json'), true);

        for ($i=0; $i < count($this->countries); $i++) { 
            
            if ($this->countries[$i]['code'] == $store->country) {
                unset($this->countries[$i]);
            }

        }

        $this->domestic_delivery_from = '1';
        $this->domestic_delivery_to = '7';

        $this->international = [[
            'country' => 'Everywhere',
            'international_free_shipping' => false,
            'international_shipping_cost' => '',
            'international_additional_shipping_cost' => '',
            'international_delivery_from' => '7',
            'international_delivery_to' => '21',
        ]];

    }
    
    public function add_shipping(){

        $this->addModalFormVisable = true;

    }

    public function additional_shipping(){
        $this->international[] = [
            'country' => 'Everywhere',
            'international_free_shipping' => false,
            'international_shipping_cost' => '',
            'international_additional_shipping_cost' => '',
            'international_delivery_from' => '7',
            'international_delivery_to' => '21',
        ];
    }

    public function remove_additional_shipping($key){
        unset($this->international[$key]);
    }

    public function new()
    {

        $this->validate();

        $store = get_store()->first();

        $domestic = new ShippingDomestic;
        $domestic->store_id = $store->id;
        $domestic->name = ($this->name) ? $this->name : "Untitled";
        $domestic->origin = $store->country;
        $domestic->processing = $this->processing;
        $domestic->free_shipping = $this->domestic_free_shipping;
        $domestic->cost = ($this->domestic_shipping_cost) ? $this->domestic_shipping_cost : 0.00;
        $domestic->additional_cost = ($this->domestic_additional_shipping_cost) ? $this->domestic_additional_shipping_cost : 0.00;
        $domestic->delivery_from = $this->domestic_delivery_from;
        $domestic->delivery_to = $this->domestic_delivery_to;
        $domestic->save();

        if (isset($this->international)) {
            
            foreach ($this->international as $key) {

                $international = new ShippingInternational;
                $international->shipping_id = $domestic->id;
                $international->origin = $key['country'];
                $international->free_shipping = $key['international_free_shipping'];
                $international->cost = ($key['international_shipping_cost']) ? $key['international_shipping_cost'] : 0.00;
                $international->additional_cost = ($key['international_additional_shipping_cost']) ? $key['international_additional_shipping_cost'] : 0.00;
                $international->delivery_from = $key['international_delivery_from'];
                $international->delivery_to = $key['international_delivery_to'];
                $international->save();

            }

        }
        $this->addModalFormVisable = false;
        $this->emit('refreshComponent');

        $this->name = '';
        $this->domestic_shipping_cost = '';
        $this->domestic_additional_shipping_cost = '';
        $this->domestic_free_shipping = false;
        $this->international = [[
            'country' => 'Everywhere',
            'international_free_shipping' => false,
            'international_shipping_cost' => '',
            'international_additional_shipping_cost' => '',
            'international_delivery_from' => '7',
            'international_delivery_to' => '21',
        ]];


        $this->notification([
            'title'       => 'Saved!',
            'description' => 'Shipping Method was successfully saved',
            'icon'        => 'success',
        ]);

    }

    public function render()
    {
        return view('livewire.shipping.listing-shipping');
    }
}
