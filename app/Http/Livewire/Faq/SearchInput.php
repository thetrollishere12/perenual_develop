<?php

namespace App\Http\Livewire\Faq;

use Livewire\Component;
use App\Models\Faq;

class SearchInput extends Component
{

    public $search;

    public function render()
    {

        $query = Faq::orderBy('seen','DESC')->limit(20)->get();

        if ($this->search) {
            $query = Faq::where('question','LIKE','%'.$this->search.'%')->limit(20)->get();
        }
        
        return view('livewire.faq.search-input',['queries'=>$query]);
    }
}
