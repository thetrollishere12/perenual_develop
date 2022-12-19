<?php

namespace App\Http\Livewire\Profile\User\Purchase;

use Livewire\Component;
use App\Models\Rating;
use WireUi\Traits\Actions;
use Auth;

class Refund extends Component
{

    protected $listeners = ['openModal'];

    use Actions;

    public $order = [];
    public $reviews;

    public $myModal = false;

    public function stars($number,$key){

        $this->order['order_product'][$key]['stars'] = $number;

    }

    public function openModal($order){
        $orders = json_decode($order,true);

        $products = [];

        foreach($orders['order_product'] as $order){

            $count = Rating::where('number',$orders['number'])->where('sku',$order['sku'])->get();

            if($count->count()>0){
                continue;
            }

            $products[] = [
                'stars' => 5,
                'comment' => null,
                'sku' => $order['sku'],
                'product_id' => $order['product_id'],
                'product_image' => $order['product_image'],
                'product_default_image' => $order['product_default_image'],
                'name' => $order['name']
            ];

        }

        $this->order = [
            "number" => $orders['number'],
            "store_id" => $orders['store_id'],
            "user_id" => $orders['user_id'],
            "order_product" => $products
        ];

        if(count($this->order['order_product']) > 0){
            $this->myModal = true;
        }else{
            $this->notification([
                'title'       => 'Already Submitted!',
                'description' => 'You already submitted a review for this order',
                'icon'        => 'error',
            ]);
        }

    }

    public function submit(){

        $this->validate([
            'order.order_product.*.stars' => 'required|numeric|min:1|max:5',
            'order.order_product.*.comment' => 'string|nullable'
        ]);

        try{

            foreach($this->order['order_product'] as $order){

                $rating = new Rating;
                $rating->user_id = Auth::id();
                $rating->store_id = $this->order['store_id'];
                $rating->number = $this->order['number'];
                $rating->sku = $order['sku'];
                $rating->ratings = $order['stars'];
                $rating->comment = $order['comment'];
                $rating->save();

            }

            $this->notification([
                'title'       => 'Review submitted!',
                'description' => 'You have successfully submitted your review for this order',
                'icon'        => 'success',
            ]);

        }catch(\Exception $e){

            $this->notification([
                'title'       => 'There was an error!',
                'description' => $e->getMessage(),
                'icon'        => 'error',
            ]);

        }

        return $this->myModal = false;

    }

    public function render()
    {



        return view('livewire.profile.user.purchase.refund');
    }
}
