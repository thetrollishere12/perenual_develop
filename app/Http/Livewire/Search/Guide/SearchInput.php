<?php

namespace App\Http\Livewire\Search\Guide;

use Livewire\Component;
use App\Models\SpeciesCareGuide;
use Livewire\WithPagination;

class SearchInput extends Component
{
    use WithPagination;
    public $search;
    public $perPage = 32;
    public $pages = 1;

    protected $queryString = [
        'search' => ['except' => ''],
        'pages' => ['except' => 1, 'as' => 'pages'],
    ];

    public function loadMore()
    {

        $this->pages++;
        
    }

    public function render()
    {


        $param = (object)[
            'search'=>$this->search,
        ];

        $queries = SpeciesCareGuide::when($this->search != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('common_name','like','%'.$this->search.'%')
                ->orWhere('scientific_name','like','%'.$this->search.'%');
            });

        })
        ->paginate($this->pages*$this->perPage);


        return view('livewire.search.guide.search-input',[
            'queries'=>$queries
        ]);
    }

}
