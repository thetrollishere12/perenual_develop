<?php

namespace App\Http\Livewire\Search\Component\Species;

use Livewire\Component;
use Carbon\Carbon;

class Season extends Component
{

    public $queries;
    public $month;

    public function mount(){
        // $this->month = Carbon::now()->month;

        // for($iM =1;$iM<=12;$iM++){

        //     dd(date("M", strtotime("$iM/12/10")));

        // }
        // dd(date('n', strtotime('May')));

        $flowering_month = $this->queries->flowering_month ?? [];

        $this->queries->flowering_month = collect($flowering_month)->map(function ($flowering_month) {
            return Carbon::parse($flowering_month)->format('n');
        })->toArray();

        $harvesting_month = $this->queries->harvesting_month ?? [];

        $this->queries->harvesting_month = collect($harvesting_month)->map(function ($harvesting_month) {
            return Carbon::parse($harvesting_month)->format('n');
        })->toArray();


        // Alternative

        // $flowering_month = $this->queries->flowering_month ?? [];

        // foreach ($flowering_month as $key => $f_month) {
            
        //     $flowering_month[$key] = date('n', strtotime($f_month));

        // }

        // $this->queries->flowering_month = $flowering_month;

        // $harvesting_month = $this->queries->harvesting_month ?? [];

        // foreach ($harvesting_month as $key => $h_month) {
            
        //     $harvesting_month[$key] = date('n', strtotime($h_month));

        // }

        // $this->queries->harvesting_month = $harvesting_month;

    }

    public function render()
    {
        return view('livewire.search.component.species.season');
    }
}
