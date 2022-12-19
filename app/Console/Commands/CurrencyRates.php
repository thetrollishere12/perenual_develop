<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Http;

class CurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Global Currency Rates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $currencies = ['CAD','USD','GBP','EUR','CHF','AUD'];

        foreach ($currencies as $c) {

            $currency = Http::get('https://v6.exchangerate-api.com/v6/'.env('EXCHANGERATEAPI').'/latest/'.$c);

            $response = json_decode($currency->getBody());

            foreach ($response->conversion_rates as $key => $value) {
         
                 $count = DB::table('currency_rates')->where('base_currency',$c)->where('foreign_currency',$key)->get();
                if (count($count) > 0) {
                    DB::table('currency_rates')->where('base_currency',$c)->where('foreign_currency',$key)->update([
                        'rate'=>$response->conversion_rates->$key
                    ]);
                }else{
                    DB::table('currency_rates')->insert([
                        'base_currency'=>$c,
                        'foreign_currency'=>$key,
                        'rate'=>$response->conversion_rates->$key
                    ]);
                }
            }

            echo "Currency ".$c." completed " ;

        }

    }
}
