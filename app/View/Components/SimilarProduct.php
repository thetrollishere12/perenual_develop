<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Product;
use App\Models\Rating;
class SimilarProduct extends Component
{

    public $style;
    /**
     * Create a new component instance.
     * @param  string  $style
     * @return void
     */
    public function __construct($style)
    {
        $this->style = $style;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {   
        $similars = Product::where('style',$this->style)->inRandomOrder()->take(10)->get();

        foreach ($similars as $key => $value) {

            $similars[$key]->store = get_store_by_id($value->store_id)->first();

            get_store_rating($similars[$key]->store,$value->store_id);

            $similars[$key]->shipping = check_if_free_shipping($value->shippingMethod);

        }

        return view('components.similar-product',["similars"=>$similars]);
    }
}
