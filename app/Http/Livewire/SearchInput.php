<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\SearchQuery;
use WireUi\Traits\Actions;

class SearchInput extends Component
{   
    use Actions;
    public $search;
    public $class;

    public function submit(){

        if (strlen($this->search) === 0) {
            $this->notification([
                'title'       => 'There was an error',
                'description' => 'The search input field cannot be empty',
                'icon'        => 'error',
            ]);
            return $this->addError('search', 'The search input field cannot be empty');
        }

        if (strlen($this->search) < 4) {
            $this->notification([
                'title'       => 'There was an error',
                'description' => 'The search input field must have a minimum 4 characters',
                'icon'        => 'error',
            ]);
            return $this->addError('search', 'The search input field must have a minimum 4 characters');
        }

        return Redirect('marketplace?search='.$this->search);

    }

    public function render()
    {

        $query = collect([]);

        if ($this->search) {
            $query = SearchQuery::where('query','LIKE','%'.$this->search.'%')->limit(10)->get()->unique('query');
        }

        return view('livewire.search-input',['queries'=>$query]);
    }
}
