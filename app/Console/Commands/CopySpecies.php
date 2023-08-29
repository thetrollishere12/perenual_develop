<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Species;
use Illuminate\Support\Arr;

class CopySpecies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CopySpecies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy from Copy Species to Species';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $copy = DB::table('copy_species')->get();

        foreach($copy as $key => $value) {
            Species::where('id',$value->id)->update([
                'watering_general_benchmark'=>$value->watering_general_benchmark,
                'seeds'=>$value->seeds
            ]);
        }

    }
}