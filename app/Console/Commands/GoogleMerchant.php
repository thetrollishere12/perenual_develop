<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Http;
use App\Models\GoogleMerchant as GMerchant;

class GoogleMerchant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:googleMerchant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        set_time_limit(0);

        $g = GMerchant::all();

        foreach ($g as $gg) {

            if ($gg->website && !str_contains($gg->website, 'http')) {

                try {

                    $h = Http::get($gg->website);
                
                    GMerchant::find($gg->id)->update([
                        'website'=>'https://'.$gg->website
                    ]);

                }catch(\Exception $e){
                  
                    GMerchant::find($gg->id)->update([
                        'website'=>'http://'.$gg->website
                    ]);

                }

                echo 'Done '.$gg->id;

            }

        }

    }
}
