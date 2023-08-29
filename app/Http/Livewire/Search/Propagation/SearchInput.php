<?php

namespace App\Http\Livewire\Search\Propagation;

use Livewire\Component;
use App\Models\PropagationMethod;
use Livewire\WithPagination;

class SearchInput extends Component
{

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

        $queries = PropagationMethod::when($this->search != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('name','like','%'.$this->search.'%')
                ->orWhere('description','like','%'.$this->search.'%');
            });

        })
        ->paginate($this->pages*$this->perPage);

        return view('livewire.search.propagation.search-input',[
            'queries'=>$queries
        ]);

    }
}
