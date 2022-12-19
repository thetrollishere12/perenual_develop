<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Product;
use App\Models\ShippingDomestic;
use App\Models\ShippingInternational;

use WireUi\Traits\Actions;

class ShippingList extends Component
{

    use Actions;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public $checkbox;
    public $class;
    public $shippingMethod;

    public function delete_shipping($id){

        $store = get_store()->first();

        $product = Product::where('shippingMethod',$id)->where('store_id',$store->id)->get();

        if ($product->count() > 0) {

            $this->notification([
                'title'       => 'Warning Cannot Delete!',
                'description' => 'Method is attached to one of your listing',
                'icon'        => 'warning',
                'iconColor'   => 'text-warning-400'
            ]);

            return $this->addError('error', 'Method is attached to one of your listings');
        }else{
            ShippingDomestic::where('id',$id)->where('store_id',$store->id)->delete();
            ShippingInternational::where('shipping_id',$id)->delete();

            $this->notification([
                'title'       => 'Deleted!',
                'description' => 'Shipping Method successfull deleted',
                'icon'        => 'x-circle',
                'iconColor'   => 'text-negative-400'
            ]);

        }

    }

    public function render()
    {
        return view('livewire.shipping-list',['shipping'=>get_shipping()]);
    }
}
