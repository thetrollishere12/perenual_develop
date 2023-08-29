<?php

namespace App\Http\Livewire\Admin\Merchant;

use Livewire\Component;
use App\Models\EtsyMerchant;
use Livewire\WithPagination;

class Etsy extends Component
{

    use WithPagination;

    public $check;

    public function check($key,$status){

        EtsyMerchant::where('id',$key)->update([
            'checked'=>$status
        ]);

    }

    public function render()
    {
        return view('livewire.admin.merchant.etsy',['merchants'=>EtsyMerchant::paginate(10)]);
    }
}
