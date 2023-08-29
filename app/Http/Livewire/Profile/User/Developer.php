<?php

namespace App\Http\Livewire\Profile\User;

use Livewire\Component;
use App\Models\ApiCredentialKey;
use Auth;
use WireUi\Traits\Actions;
use Storage;
use Stripe\Stripe;
use Carbon\Carbon;

use Livewire\WithChartJS;
use Orchid\Screen\Fields\Chart as OrchidChart;
use App\Models\ApiCallLog;

class Developer extends Component
{

    public $credential;
    public $subscription_details = [];
    public $details;


    public $subscriptions = [];

    use Actions;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(){

        $this->credential = Auth::user()->api_key()->first();

        $subscription = user_is_subscribed_type('subscription');

        if ($subscription->count() > 0) {

            switch ($subscription->first()->payment_method) {
                case 'Stripe':

                    Stripe::setApiKey(env('STRIPE_SECRET'));

                    $stripe_id = $subscription->first()->stripe_id;

                    $account = \Stripe\Subscription::retrieve($stripe_id);

                    $this->subscriptions['subscription'] = [
                        'details' => subscription_details($subscription->first()->name, 'subscription'),
                        'payment_method' => 'Stripe',
                        'subscription' => $subscription->first(),
                        'user' => auth()->user(),
                        'name' => $subscription->first()->name,
                        'period_end' => $account->current_period_end,
                        'period_valid' => $account->current_period_start,
                        'amount' => number_format($account->plan->amount / 100, 2),
                        'active' => $account->plan->active,
                        'cancel' => $account->cancel_at_period_end,
                    ];

                break;
                case 'Paypal':

                    $bearer_token = paypal_bearer_token();

                    $subscription_details = paypal_subscription($subscription->first()->paypal_id,$bearer_token);

                    $this->subscriptions['subscription'] = [
                        'details' => subscription_details($subscription->first()->name, 'subscription'),
                        'payment_method' => 'Paypal',
                        'subscription' => $subscription->first(),
                        'user' => auth()->user(),
                        'name' => $subscription->first()->name,
                        'period_end' => Carbon::parse($subscription_details->billing_info->last_payment->time)->addDays(30),
                        'period_valid' => $subscription_details->billing_info->last_payment->time,
                        'amount' => number_format($subscription_details->billing_info->last_payment->amount->value, 2),
                        'active' => $subscription_details->status,
                        'cancel' => $subscription_details->status,
                    ];

                break;
                default:
                break;
            }

        }else{
            $this->subscriptions['subscription'] = [
                'details' => subscription_details('personal','subscription')
            ];

        }


        if(Auth::user()->hasAccess('platform.systems.roles')){

            $subscription = user_is_subscribed_type('subscription_identify');

            if ($subscription->count() > 0) {

                switch ($subscription->first()->payment_method) {
                    case 'Stripe':
                   
                        Stripe::setApiKey(env('STRIPE_SECRET'));

                        $stripe_id = $subscription->first()->stripe_id;

                        $account = \Stripe\Subscription::retrieve($stripe_id);

                        $date= Carbon::now();

                        $this->subscriptions['subscription_identify'] = [
                            'details' => subscription_details($subscription->first()->name, 'subscription_identify'),
                            'payment_method' => 'Stripe',
                            'subscription' => $subscription->first(),
                            'user' => auth()->user(),
                            'name' => $subscription->first()->name,
                            'period_end' => $account->current_period_end,
                            'period_valid' => $account->current_period_start,
                            'amount' => number_format(($account->plan->amount/100)*auth()->user()->subscription($subscription->first()->name)->usageRecords()->first()->total_usage, 2),
                            'active' => $account->plan->active,
                            'cancel' => $account->cancel_at_period_end,
                        ];

                    break;
                    default:
                    break;
                }

            }else{
                $this->subscriptions['subscription_identify'] = [
                    'details' => subscription_details('personal','subscription_identify')
                ];

            }

        }


    }

    public function request(){

        try{

        $key = random_id('sk-');

        ApiCredentialKey::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],
            [
                'key' => $key
            ]
        );

        $this->dialog([
            'title' => 'Successfully Generated. Please copy it.',
            'description'=> $key,
            'icon' => 'success',
            // close: 'b',
            "close" => [
                "label" => 'Done',
                "positive" => true
            ]
        ]);

        }catch(\Exception $e){

            $this->notification([
                'title'       => 'There Was An Error',
                'description' => 'There was an error trying to generate a key. Please try again or contact us',
                'icon'        => 'error',
            ]);

        }

        $this->credential = Auth::user()->api_key()->first();


    }

    public function cancel_subscription($type){

        cancel_user_subscription($type);

        $this->notification([
            'title'       => 'Subscription was resumed',
            'description' => 'Your subscription was successfully resumed',
            'icon'        => 'success',
        ]);

    }

    public function resume_subscription($type){

        resume_user_subscription($type);

        $this->notification([
            'title'       => 'Subscription was cancelled',
            'description' => 'Your subscription was successfully cancelled',
            'icon'        => 'error',
        ]);

    }

    public function render()
    {
        // Past 3 days
        // $start = Carbon::now()->subDay(30);
        // $end = Carbon::now();

        // Current Month
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $chartData = ApiCallLog::where('request_uri', 'LIKE', '%/api/identify/%')
            ->where('user_id', Auth::user()->id)
            ->countByDays($start, $end, 'created_at')
            ->toChart('Identify Calls');
        
        // Modify the labels to remove the years
        $chartData['labels'] = array_map(function ($label) {
            return Carbon::parse($label)->format('M d');
        }, $chartData['labels']);

        return view('livewire.profile.user.developer')->with('chartData', $chartData);
    }
}
