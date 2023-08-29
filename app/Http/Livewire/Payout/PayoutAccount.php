<?php

namespace App\Http\Livewire\Payout;

use Livewire\Component;
use Storage;
use App\Models\PayoutAccount as PayoutAccounts;
use Auth;
use WireUi\Traits\Actions;

class PayoutAccount extends Component
{   
    use Actions;

    public $redirect;
    public $required;

    public $first_name;
    public $last_name;
    public $email;
    public $country;
    public $line1;
    public $line2;
    public $city;
    public $state_county_province_region;
    public $postal_zip;
    public $year;
    public $month;
    public $day;
    public $phone;

    public function mount(){
        $this->json = json_decode(Storage::disk('local')->get('json/country.json'), true);

        $this->country = $this->json[0]['code'];
        $this->state_county_province_region = $this->json[0]['states'][0];

        // Something is wrong with the api and comes back invalid api if the token expires giving errors. Temporary pause
        try {
            if ($etsy = Auth::user()->connected_etsy()->first()) {
                $new_bearer_token = etsy_token_refresh($etsy->shop_id);
                $address = etsy_get_user_addresses($new_bearer_token);

                if (!$address->error && $address->count > 0) {

                    foreach($address->results as $addy){
                        if ($addy->is_default_shipping_address == true) {

                            $this->first_name = explode(" ",$addy->name)[0];
                            $this->last_name = explode(" ",$addy->name)[1];

                            $this->country = $addy->iso_country_code;
                            $this->line1 = $addy->first_line;
                            $this->line2 = $addy->second_line;
                            $this->city = $addy->city;
                            $this->state_county_province_region = $addy->state;
                            $this->postal_zip = $addy->zip;

                        }
                    }

                }
            }
        } catch (Exception $e) {
   
        }

    }

    public function submit(){
            
            $this->validate([
                'first_name' => 'required|max:100',
                'last_name' => 'required|max:100',
                'day' => 'numeric|required|min:1|max:31',
                'month'=>'numeric|required|min:1|max:12|',
                'year' => 'numeric|min:'.date('Y', strtotime('-150 years')).'|max:'.date('Y'),
                'line1' => 'required|string|max:100',
                'line2' => 'nullable|string|max:100',
                'country' => 'required|max:100',
                'state_county_province_region' => 'required',
                'city' => 'required|max:100',
                'postal_zip' => 'required|max:100',
                'phone' => 'required|max:20'
            ]);

            if (PayoutAccounts::where('user_id',Auth::id())->count() > 0) {

                $this->addError('account', 'You already submitted details');
                $this->notification([
                    'title'       => 'Already Submitted',
                    'description' => 'You already submitted details '.(($this->redirect) ? 'and now will be redirected' : ''),
                    'icon'        => 'success'
                ]);

                if ($this->redirect) {
                    return Redirect($this->redirect);
                }

            }

            try {

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $account = \Stripe\Account::create([
              'country' => $this->country,
              'type' => 'custom',
              'business_type'=> 'individual',
              'business_profile' => [
                'mcc' => 5261,
                'url' => env('APP_URL')
              ],
              'capabilities' => [
                'card_payments' => [
                  'requested' => true,
                ],
                'transfers' => [
                  'requested' => true,
                ],
              ],
              'tos_acceptance' => [
                'date' => time(),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
              ],
              'individual'=>[
                'dob'=>[
                    "day"=> $this->day,
                    "month"=> $this->month,
                    "year"=> $this->year
                ],
                'email'=>Auth::user()->email,
                'first_name'=>$this->first_name,
                'last_name'=>$this->last_name,
                'phone'=>$this->phone,
                'address'=>[
                    "city"=> $this->city,
                    "country"=> $this->country,
                    "line1"=> $this->line1,
                    "line2"=> $this->line2,
                    "postal_code"=> $this->postal_zip,
                    "state"=> $this->state_county_province_region
                ]
              ],
              'settings'=>[
                'payouts'=>[
                    'debit_negative_balances' => true
                ]
              ]
            ]);

            $payout = new PayoutAccounts;
            $payout->user_id = Auth::id();
            $payout->payment_method = 'Stripe';
            $payout->account_number = $account->id;
            $payout->save();

            $this->notification([
                'title'       => 'Successfully Submitted',
                'description' => 'You successfully submitted '.(($this->redirect) ? 'and now will be redirected' : ''),
                'icon'        => 'success'
            ]);

            if ($this->redirect) {
                return Redirect($this->redirect);
            }

            } catch (\Exception $e) {

                $this->notification([
                    'title'       => 'Ran into a problem',
                    'description' => 'There was an error please contact us (140)',
                    'icon'        => 'error'
                ]);

                return $this->addError('error',$e->getMessage());
            }

    }

    public function country(){

        foreach($this->json as $country){
            if ($country['code'] == $this->country) {
                $this->state_county_province_region = $country['states'][0];
            }
        }

    }

    public function render()
    {
        if ($this->country) {

            foreach($this->json as $key => $country){
                if ($country['code'] == $this->country) {
                    $this->spr = $country;
                }
            }
        }

        return view('livewire.payout.payout-account');
    }
}
