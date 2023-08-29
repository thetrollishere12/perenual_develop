<?php

namespace App\Http\Livewire\Search\Faq;

use Livewire\Component;
use App\Models\ArticleFaq;
use App\Models\ArticleFaqImage;
use Storage;
use WireUi\Traits\Actions;

class SearchInput extends Component
{
    use Actions;
    public $search;
    public $queries;
    public $page = 1;
    
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function delete($id){

        $faq = ArticleFaq::findOrFail($id);

        ArticleFaq::findOrFail($faq->id)->delete();

        ArticleFaqImage::where('article_id',$id)->delete();

        $this->emit('refreshComponent');

        if ($faq->image_path) {
            Storage::disk('public')->deleteDirectory($faq->image_path);
        }

        return $this->notification([
            'title'       => 'Deleted!',
            'description' => 'Article was deleted',
            'icon'        => 'error',
        ]);

    }

    public function mount(){
        $this->queries = ArticleFaq::orderBy('id','DESC')->take(24)->get();
        $this->total_page = ceil(ArticleFaq::count()/24);
    }

    public function search(){
        $this->queries = ArticleFaq::where('tags','like','%'.$this->search.'%')->orWhere('question','like','%'.$this->search.'%')->orWhere('answer','like','%'.$this->search.'%')->take(24)->get();

        $this->total_page = ceil(ArticleFaq::where('tags','like','%'.$this->search.'%')->orWhere('question','like','%'.$this->search.'%')->orWhere('answer','like','%'.$this->search.'%')->count()/24);

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
        return view('livewire.search.faq.search-input');
    }

    public function dehydrate(){
        if($this->total_page > 1){
            $this->next_queries = ArticleFaq::where('tags','like','%'.$this->search.'%')->orWhere('question','like','%'.$this->search.'%')->orWhere('answer','like','%'.$this->search.'%')->skip($this->page*24)->orderBy('id','DESC')->take(24)->get();
        }
    }

}
