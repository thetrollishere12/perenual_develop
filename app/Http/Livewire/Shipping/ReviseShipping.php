<?php

namespace App\Http\Livewire\Shipping;

use Livewire\Component;
use Storage;
use App\Models\ShippingDomestic;
use App\Models\ShippingInternational;
use WireUi\Traits\Actions;
class ReviseShipping extends Component
{
    use Actions;
    public $editModalFormVisable = false;

    public $shipping;
    public $domestic_free_shipping = false;
    public $processing;
    public $domestic_shipping_cost = 0.00;
    public $domestic_additional_shipping_cost = 0.00;
    public $domestic_delivery_from;
    public $domestic_delivery_to;

    public $current_international = [];
    public $current_delete  = [];


    public $international = [];


    protected $listeners = ['edit_shipping'];

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
    }

    public function edit_shipping($pid){

        $this->shipping = get_shipping_id($pid)->first();

        $this->name = $this->shipping->name;
        $this->processing = $this->shipping->processing;
        $this->domestic_free_shipping = $this->shipping->free_shipping;
        $this->domestic_shipping_cost = $this->shipping->cost;
        $this->domestic_additional_shipping_cost = $this->shipping->additional_cost;
        $this->domestic_delivery_from = $this->shipping->delivery_from;
        $this->domestic_delivery_to = $this->shipping->delivery_to;

        $this->current_international = [];
        foreach ($this->shipping->international as $international) {

            $this->current_international[] = [
                'id' => $international->id,
                'origin' => $international->origin,
                'international_free_shipping' => $international->free_shipping,
                'international_shipping_cost' => $international->cost,
                'international_additional_shipping_cost' => $international->additional_cost,
                'international_delivery_from' => $international->delivery_from,
                'international_delivery_to' => $international->delivery_to,
            ];
        }

        $this->international = [];

        $this->editModalFormVisable = true;
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

    public function delete_current($i,$i_id){
        unset($this->current_international[$i]);
        $this->current_delete[] = $i_id;
    }

    public function update(){

        $this->validate();
        
        // Domestic
        ShippingDomestic::where('id',$this->shipping->id)->update([
            'name'=>$this->name,
            'processing'=>$this->processing,
            'free_shipping'=>$this->domestic_free_shipping,
            'cost'=>($this->domestic_shipping_cost) ? $this->domestic_shipping_cost : 0.00,
            'additional_cost'=>($this->domestic_additional_shipping_cost) ? $this->domestic_additional_shipping_cost : 0.00,
            'delivery_from'=>$this->domestic_delivery_from,
            'delivery_to'=>$this->domestic_delivery_to
        ]);

        // Add new international
        if (isset($this->international)) {
            
            foreach ($this->international as $key) {

                $international = new ShippingInternational;
                $international->shipping_id = $this->shipping->id;
                $international->origin = $key['country'];
                $international->free_shipping = $key['international_free_shipping'];
                $international->cost = ($key['international_shipping_cost']) ? $key['international_shipping_cost'] : 0.00;
                $international->additional_cost = ($key['international_additional_shipping_cost']) ? $key['international_additional_shipping_cost'] : 0.00;
                $international->delivery_from = $key['international_delivery_from'];
                $international->delivery_to = $key['international_delivery_to'];
                $international->save();

            }

        }

        if (isset($this->current_international)) {
            
            foreach($this->current_international as $current){
                
                ShippingInternational::where('id',$current['id'])->update([
                    'cost'=>$current['international_shipping_cost'],
                    'free_shipping' => $current['international_free_shipping'],
                    'additional_cost'=>($current['international_additional_shipping_cost'])? $current['international_additional_shipping_cost'] : 0.00,
                    'delivery_from'=>($current['international_delivery_from'])? $current['international_delivery_from'] : 0.00,
                    'delivery_to'=>$current['international_delivery_to']
                ]);
            }

        }

        if(isset($this->current_delete)){

            foreach ($this->current_delete as $delete_id) {
                ShippingInternational::where('id',$delete_id)->delete();
            }

        }

        $this->editModalFormVisable = false;
        $this->emit('refreshComponent');

        $this->notification([
            'title'       => 'Saved!',
            'description' => 'Shipping Method was successfully saved',
            'icon'        => 'success',
        ]);
        
    }

    public function render()
    {
        return view('livewire.shipping.revise-shipping');
    }
}
