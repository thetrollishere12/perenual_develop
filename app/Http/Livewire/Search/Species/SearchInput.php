<?php

namespace App\Http\Livewire\Search\Species;

use Livewire\Component;
use App\Models\Species;
use WireUi\Traits\Actions;
use Livewire\WithPagination;

class SearchInput extends Component
{
    use Actions;
    use WithPagination;
    public $search;
    public $poisonous;
    public $edible;
    public $indoor;
    public $medicinal;
    public $rare;
    public $fruits;
    public $flowers;
    public $sun_exposure;
    public $watering;
    public $cycle;
    public $growth_rate;

    public $perPage = 32;
    public $pages = 1;

    public $hardiness;

    protected $queryString = [
        'search' => ['except' => ''],
        'pages' => ['except' => 1, 'as' => 'pages'],
        'poisonous' => ['except' => ''],
        'edible' => ['except' => ''],
        'indoor' => ['except' => ''],
        'medicinal' => ['except' => ''],
        'rare' => ['except' => ''],
        'fruits' => ['except' => ''],
        'flowers' => ['except' => ''],
        'sun_exposure' => ['except' => ''],
        'watering' => ['except' => ''],
        'cycle' => ['except' => ''],
        'growth_rate' => ['except' => ''],
        'hardiness' => ['except' => ''],
    ];

    public $filterFormVisable = false;
    
    protected $listeners = ['filter','hardinessData'];

    public function filter(){
        $this->filterFormVisable = true;
    }

    public function loadMore()
    {

        $this->pages++;
        
    }

    public function hardinessData($min,$max){
        $this->hardiness = (int)$min.'-'.(int)$max;
        $this->skipRender();
    }

    public function render()
    {

        $param = (object)[
            'search'=>$this->search,
            'poisonous'=>$this->poisonous,
            'edible'=>$this->edible,
            'indoor'=>$this->indoor,
            'medicinal'=>$this->medicinal,
            'rare'=>$this->rare,
            'fruits'=>$this->fruits,
            'flowers'=>$this->flowers,
            'sun_exposure'=>$this->sun_exposure,
            'watering'=>$this->watering,
            'cycle'=>$this->cycle,
            'growth_rate'=>$this->growth_rate,
            'hardiness'=>$this->hardiness
        ];


        $queries = Species::when($this->search != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('common_name','like','%'.$this->search.'%')
                ->orWhere('scientific_name','like','%'.$this->search.'%')
                ->orWhere('other_name','like','%'.$this->search.'%');
            });

        })
        ->when($this->poisonous != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('poisonous_to_humans',$param->poisonous)
                ->orWhere('poisonous_to_pets',$param->poisonous);
            });

        })
        ->when($this->edible != null,function($q) use ($param){
            $q->where(function($query) use ($param){
                $query->where('edible_leaf',$param->edible)
                ->orWhere('edible_fruit',$param->edible);
            });
        })
        ->when($this->indoor != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('indoor',$param->indoor);
            });

        })
        ->when($this->medicinal != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('medicinal',$param->medicinal);
            });

        })
        ->when($this->rare != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('rare',$param->rare);
            });

        })
        ->when($this->fruits != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('fruits',$param->fruits);
            });

        })
        ->when($this->flowers != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('flowers',$param->flowers);
            });

        })
        ->when($this->sun_exposure != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('sunlight','LIKE','%'.$param->sun_exposure.'%');
            });

        })
        ->when($this->watering != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('watering','LIKE','%'.$param->watering.'%');
            });

        })
        ->when($this->cycle != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('cycle','LIKE','%'.$param->cycle.'%');
            });

        })
        ->when($this->growth_rate != null,function($q) use ($param){

            $q->where(function($query) use ($param){
                $query->where('growth_rate','LIKE','%'.$param->growth_rate.'%');
            });

        })
        ->when($this->hardiness,function($q) use($param){
            
            $hardiness = explode("-",$param->hardiness);

            if (count($hardiness) == 1) {
                $hardiness[1] = $hardiness[0];
            }
        
            $q->where(function($query) use($hardiness){
                $query->where('hardiness->min','REGEXP','[[:<:]](' . implode('|', range($hardiness[0], $hardiness[1])) . ')([A-Za-z]?)')
                ->where('hardiness->max','REGEXP','[[:<:]](' . implode('|', range($hardiness[0], $hardiness[1])) . ')([A-Za-z]?)');
            });

        })

        ->paginate($this->pages*$this->perPage);

        $this->filterFormVisable = false;

        return view('livewire.search.species.search-input',[
            'queries'=>$queries
        ]);
    }

}