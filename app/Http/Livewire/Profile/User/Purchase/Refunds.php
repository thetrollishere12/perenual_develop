<?php

namespace App\Http\Livewire\Profile\User\Purchase;

use Livewire\Component;
use App\Models\RefundHistory;

use Carbon\Carbon;
use WireUi\Traits\Actions;

class Refunds extends Component
{


    use Actions;
    public $number;
    public $products = [];
    public $comment;
    public $refund_method = "refund";
    public $reason = 1;

    protected $listeners = ['refreshComponent' => '$refresh','mount'];

    public function mount(){

        $this->products = [];

        $this->order = get_sold_full_order($this->number)->first()->toArray();

        foreach ($this->order['order_products'] as $key => $product) {
            $history = RefundHistory::where('order_number',$this->number)->where('order_product_sku',$product['sku']);

            $this->products[] = [
                "sku"=>$product['sku'],
                "name"=>$product['name'],
                "currency"=>$product['currency_rate']['base_currency'],
                "price"=>$product['to_price'],
                "quantity"=>$product['quantity'],
                "product_image"=>$product['product_image'],
                "product_default_image"=>$product['product_default_image'],
                "fee_breakdown"=>$this->order['fee_breakdown'],
                "type" => "products",
                "refunded"=>$history->sum('reversed_amount'),
                "refunding"=>"",
                "max"=>number_format(round(($product['quantity']*$product['to_price'])-$history->sum('reversed_amount'),2), 2, '.', '')
            ];
        }

        $history = RefundHistory::where('order_number',$this->number)->where('type','shipping');
        $this->shipping = [
            "currency"=>$this->order['currency'],
            "amount" => $this->order['shipping'],
            "fee_breakdown"=>$this->order['fee_breakdown'],
            "type" => "shipping",
            "refunded" => $history->sum('reversed_amount'),
            "refunding"=>"",
            "max"=>number_format(round($this->order['shipping']-$history->sum('reversed_amount'),2), 2, '.', '')
        ];

        $history = RefundHistory::where('order_number',$this->number)->where('type','tax');
        $this->tax = [
            "currency"=>$this->order['currency'],
            "amount" => $this->order['tax'],
            "fee_breakdown"=>$this->order['fee_breakdown'],
            "type" => "tax",
            "refunded" => $history->sum('reversed_amount'),
            "refunding"=>"",
            "max"=>number_format(round($this->order['tax']-$history->sum('reversed_amount'),2), 2, '.', '')
        ];


    }

    public function submit(){

        $this->validate([
            'refunding'=>'required|numeric|min:0.01',
            'reversing_fee'=>'required|numeric|min:0.00',
            'reversed_flat_fee'=>'numeric|min:0.00',
            'reason' => 'required|numeric',
            'refund_method'=>'required|string',
            'comment'=>'nullable|max:1000',
        ]);

        try{

        $order = get_sold_order($this->number)->first();

        if(Carbon::parse($order['created_at'])->addDays(30)->isPast()){
            return $this->addError('error','Past eligible refund date');
        }

        $order->comment = $this->comment;
        $order->reversing_fee = bcdiv($this->reversing_fee,1,2);
        $order->reason = $this->reason;
        get_order_refund($order);
        $order->payment_details = get_order_payment_details($order);

        if ($this->refund_method == "refund") {
            
            $refund = order_refund($order,$this->refunding);

            $this->refundList = $this->products;
            $this->refundList[] = $this->shipping;
            $this->refundList[] = $this->tax;

            foreach($this->refundList as $product){
                if ($product['refunding'] > 0) {
                    $history =  new RefundHistory;
                    $history->order_number = $this->number;
                    $history->refund_id = $refund->id;
                    $history->type = $product['type'];
                    $history->reversed_amount = $product['refunding'];
                    $history->customer_reversed_amount = $product['refunding']*$order->currency_rate;
                    $history->order_product_sku = (isset($product['sku']))?$product['sku']:null;
                    $history->save();
                }
            }

            $order->refunded = ["amount"=>$this->refunding,'customer_amount'=>$order->refund_details['amount']];

        }else{

            $refund = order_refund($order,$order->remaining);
          
            $order->refunded = ["amount"=>$order->remaining,'customer_amount'=>$order->refund_details['amount']];

            ProductCancel($order);

        }

        ProductRefund($order);
        $this->emit('mount');

        $this->notification([
            'title'       => 'Refunded!',
            'description' => 'You have successfully refunded back to your customer. Please give  3-5 business days for your customer to receive it',
            'icon'        => 'success',
        ]);

        }catch(\Exception $e){
            $this->notification([
                'title'       => 'There was an error!',
                'description' => 'There was an error trying to submit a refund. Please try again or contact us',
                'icon'        => 'error',
            ]);
            return $this->addError('error', $e->getMessage());
        }

    }

    public function cancel_order(){

        foreach ($this->products as $key => $product) {
            if ($product['max'] > 0) {
                $this->products[$key]["refunding"] = ($product['quantity']*$product['price'])-$product['refunded'];
            }
        }

        $this->shipping["refunding"] = $this->shipping["amount"]-$this->shipping["refunded"];
        $this->tax["refunding"] = $this->tax["amount"]-$this->tax["refunded"];

    }


    public function render()
    {

        $this->refunding = 0;
        $this->reversing_fee = 0;
        $this->reversed_flat_fee = 0;

        foreach($this->products as $product){
            $refunding=($product['refunding'])?$product['refunding']:0;
            $this->refunding += $refunding;
            $this->reversing_fee += $refunding*$product['fee_breakdown']['percentage']/100;
        }

        $refunding=($this->shipping['refunding'])?$this->shipping['refunding']:0;
        $this->refunding += $refunding;
        $this->reversing_fee += $refunding*$this->shipping['fee_breakdown']['percentage']/100;

        $refunding=($this->tax['refunding'])?$this->tax['refunding']:0;
        $this->refunding += $refunding;
        $this->reversing_fee += $refunding*$this->tax['fee_breakdown']['percentage']/100;

        if ($this->refund_method == "full_refund" || ($this->order['total']-$this->order['reversed']) == $this->refunding) {
            $this->reversed_flat_fee = $this->shipping['fee_breakdown']['flat'];
        }

        return view('livewire.profile.user.purchase.refunds');
    }
}
