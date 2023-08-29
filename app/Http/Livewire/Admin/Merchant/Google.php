<?php

namespace App\Http\Livewire\Admin\Merchant;

use Livewire\Component;
use App\Models\GoogleMerchant;
use Livewire\WithPagination;

class Google extends Component
{

    use WithPagination;

    public $check;

    public function check($key,$status){

        GoogleMerchant::where('id',$key)->update([
            'checked'=>$status
        ]);

    }

    public function render()
    {
        return view('livewire.admin.merchant.google',['merchants'=>GoogleMerchant::paginate(10)]);
    }
}
