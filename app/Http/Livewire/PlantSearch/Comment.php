<?php

namespace App\Http\Livewire\PlantSearch;

use Livewire\Component;
use App\Models\ProductComment;

class Comment extends Component
{
    protected $listeners=['commentDeleted'=>'render'];

    public $parent_id;
    public $product_id;
    public $comment;

    public $show_child;

    public function mount($product_id,$parent_id=null){
        $this->product_id = $product_id;
        $this->parent_id = $parent_id;
    }
    public function render()
    {
        $comments=ProductComment::with('productCommentsLikes')->where('product_id',$this->product_id)->where('parent_id',$this->parent_id)->orderBy('id','asc')->get();
        return view('livewire.plant-search.comment',['comments'=>$comments]);
    }

    public function addComment(){
        ProductComment::create([
            'product_id'=>$this->product_id,
            'comment'=>$this->comment,
            'user_id'=>auth()->user()->id
        ]);
        $this->reset(['comment']);
    }
}
