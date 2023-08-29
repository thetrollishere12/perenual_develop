<?php

namespace App\Http\Livewire\Payout;

use Livewire\Component;
use Storage;
use App\Models\PayoutExternalAccount;
use WireUi\Traits\Actions;
class Bank extends Component
{   
    use Actions;
    public $bankFormVisable = false;

    public $account_number;

    protected $listeners = ['bankForm'];

    public function bankForm(){
        $this->bankFormVisable = true;
    }

    public function default($id){

        try {

        $payout = get_payout();

        $methods = PayoutExternalAccount::where('account_id',$payout->id)->get();

        foreach ($methods as $method) {
            $method->update(["default_method"=>NULL]);
        }

        $payout_setting = tap(PayoutExternalAccount::where('account_id',$payout->id)->where('id',$id))->update([
            "default_method"=>"default"
        ])->first();

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $update = \Stripe\Account::updateExternalAccount(
          $payout->account_number,
          $payout_setting->bank_id,
          ['default_for_currency' => true]
        );

        $this->notification([
            'title'       => 'Set as default!',
            'description' => 'Successfully set as default',
            'icon'        => 'success',
        ]);

        } catch (\Exception $e) {

            $this->notification([
                'title'       => 'There was a problem!',
                'description' => 'Please contact us',
                'icon'        => 'error',
            ]);

            return $this->addError('error','Error. Please contact us');
        }

    }

    public function delete($id){

        try {

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $payout = get_payout();

        $selected = PayoutExternalAccount::where('account_id',$payout->id)->where('payout_external_accounts.id',$id)->first();

        PayoutExternalAccount::where('account_id',$payout->id)->where('payout_external_accounts.id',$id)->delete();

        // after the main one got deleted. Updates on stripe backend
        $delete = \Stripe\Account::deleteExternalAccount(
          $payout->account_number,
          $selected->bank_id,
          []
        );

        $this->notification([
            'title'       => 'Deleted!',
            'description' => 'Bank account successfull deleted',
            'icon'        => 'x-circle',
            'iconColor'   => 'text-negative-400'
        ]);

        } catch (\Exception $e) {

            $this->notification([
                'title'       => 'There was a problem!',
                'description' => 'Please contact us',
                'icon'        => 'error',
            ]);

            return $this->addError('error','Error. Please contact Us');
        }
        
    }
    
    public function render()
    {

        $this->json = json_decode(Storage::disk('local')->get('json/country.json'), true);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $account = \Stripe\Account::retrieve($this->account_number,[]);

        $this->external= get_payout_external();

        return view('livewire.payout.bank',["account"=>$account]);
    }
}
