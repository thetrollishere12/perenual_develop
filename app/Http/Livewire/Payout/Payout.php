<?php

namespace App\Http\Livewire\Payout;

use Livewire\Component;
use WireUi\Traits\Actions;
class Payout extends Component
{   
    use Actions;
    public $account_number;

    public $account;

    public $ssn;

    public function ssn(){

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        
        $payout = get_payout();

        $this->validate([
            'ssn' => 'required|string|max:11'
        ]);

        try {

        $external = \Stripe\Account::update($payout->account_number,
          ['individual' => [
            'id_number' => $this->ssn,
            'ssn_last_4' => substr($this->ssn,-4)
        ]]
        );
        
        $this->notification([
            'title'       => 'Sucessfully Submitted!',
            'description' => 'Submitted for verification.',
            'icon'        => 'success',
        ]);

        } catch (\Exception $e) {
            
            $this->notification([
                'title'       => 'There was a problem!',
                'description' => 'There was an issue with your SSN',
                'icon'        => 'error',
            ]);

            return $this->addError('error', $e->getMessage());

        }

    }

    public function render()
    {

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $this->account = \Stripe\Account::retrieve($this->account_number)->toArray();
        
        return view('livewire.payout.payout');
    }
}
