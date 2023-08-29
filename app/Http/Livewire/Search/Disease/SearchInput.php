<?php

namespace App\Http\Livewire\Search\Disease;

use Livewire\Component;
use App\Models\SpeciesIssue;
use WireUi\Traits\Actions;

class SearchInput extends Component
{

    use Actions;
    public $search;
    public $queries;
    public $page = 1;

    public function mount(){

        $this->queries = SpeciesIssue::take(33)->get();
        $this->total_page = ceil(SpeciesIssue::count()/33);

    }


    public function search(){
        $this->queries = SpeciesIssue::where('common_name','like','%'.$this->search.'%')->orWhere('scientific_name','like','%'.$this->search.'%')->take(33)->get();

        $this->total_page = ceil(SpeciesIssue::where('common_name','like','%'.$this->search.'%')->orWhere('scientific_name','like','%'.$this->search.'%')->count()/33);
    }


    public function nextPage(){

        if ($this->page != $this->total_page) {
            $this->queries = $this->queries->merge($this->next_queries);
            $this->page++;
        }else{

            $this->notification([
                'title'       => 'No more results!',
                'description' => 'There was no more to show',
                'icon'        => 'x-circle',
                'iconColor'   => 'text-negative-400'
            ]);

        }

    }

    public function render()
    {
        return view('livewire.search.disease.search-input');
    }

    public function dehydrate(){
        if($this->total_page > 1){
            $this->next_queries = SpeciesIssue::where('common_name','like','%'.$this->search.'%')->orWhere('scientific_name','like','%'.$this->search.'%')->skip($this->page*33)->take(33)->get();
        }
    }

}
