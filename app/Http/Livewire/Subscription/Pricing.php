<?php

namespace App\Http\Livewire\Subscription;

use Livewire\Component;
use Storage;
use Auth;

class Pricing extends Component
{

    public $subscription;
    public $type;
    public $plan_name;

    public function mount(){

        $this->type = "monthly";
        $this->subscriptions = json_decode(Storage::disk('local')->get('json/subscription.json'), true);
        $this->plan_name = array_column($this->subscriptions, 'name');

    }

    public function subscription_type($type){
        $this->type = $type;
    }

    public function render()
    {

        $user = [];

        if (Auth::user()) {
            $user = user_is_subscribed_to($this->plan_name)->first();
            if (!$user) {
                $user = collect([(object)["name"=>"personal"]])->first();
            }
        }

        return view('livewire.subscription.pricing',['user'=>$user]);

    }
}
