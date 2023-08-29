<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\PaypalSubscription;
use Carbon\Carbon;

class PaypalSubscriptionStatusChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:PaypalSubscriptionStatusChecker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Paypal Subscriptions daily if theres any manual changes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $accounts = PaypalSubscription::where('ends_at',null)->get();

        $token = paypal_bearer_token();

        foreach ($accounts as $key => $account) {
            
            $details = paypal_subscription($account->paypal_id,$token);

            if ($details->status == "CANCELLED") {

                PaypalSubscription::where('id',$account->id)->update([
                    'ends_at'=>Carbon::parse($details->billing_info->last_payment->time)->setMonth(Carbon::now()->format('m'))
                ]);

            }

        }

    }
}
