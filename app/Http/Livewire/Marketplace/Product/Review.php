<?php

namespace App\Http\Livewire\Marketplace\Product;

use Livewire\Component;
use App\Models\Rating;

class Review extends Component
{

    public $product;


    public function render()
    {
        $this->reviews = Rating::leftJoin('users','ratings.user_id','=','users.id')
        ->select('ratings.sku','ratings.ratings','ratings.comment','ratings.created_at','users.name','users.profile_photo_path')
        ->where('ratings.store_id','=',$this->product['store_id'])->limit(5)->get();
        
        return view('livewire.marketplace.product.review');
    }
}
