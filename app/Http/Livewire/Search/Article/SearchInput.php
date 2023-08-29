<?php

namespace App\Http\Livewire\Search\Article;

use Livewire\Component;
use App\Models\Article;

use Storage;
use WireUi\Traits\Actions;

class SearchInput extends Component
{
    use Actions;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function delete($id){

        Article::where('id',$id)->delete();

        Article::where('parent_id',$id)->delete();

        $this->emit('refreshComponent');

        return $this->notification([
            'title'       => 'Deleted!',
            'description' => 'Article was deleted',
            'icon'        => 'error',
        ]);

    }

    public function approve($id){

        Article::where('id',$id)->update([
            'status'=>1
        ]);

        Article::where('parent_id',$id)->update([
            'status'=>1
        ]);

        $this->emit('refreshComponent');
        
    }   

    public function disapprove($id){

        Article::where('id',$id)->update([
            'status'=>0
        ]);

        Article::where('parent_id',$id)->update([
            'status'=>0
        ]);

        $this->emit('refreshComponent');
        
    }

    public function mount(){
        $this->queries = Article::where('parent_id',null)->take(32)->get();
    }

    public function search(){
        $this->queries = Article::where('title','like','%'.$this->search.'%')->orWhere('description','like','%'.$this->search.'%')->orWhere('tags','like','%'.$this->search.'%')->get();
    }

    public function render()
    {
        return view('livewire.search.article.search-input');
    }
}
