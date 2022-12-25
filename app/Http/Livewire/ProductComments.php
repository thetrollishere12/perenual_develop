<?php

namespace App\Http\Livewire;

use App\Models\ProductComment;
use Livewire\Component;

class ProductComments extends Component
{
    protected $listeners=['commentDeleted'=>'render'];
    public $parent_id;
    public $product_id;
    public $show_child;

    public $comment;

    public function mount($product_id=1,$parent_id=null){
        $this->product_id = $product_id;
        $this->parent_id = $parent_id;
    }
    public function render()
    {
        $comments=ProductComment::with('productCommentsLikes')->where('product_id',$this->product_id)->where('parent_id',$this->parent_id)->get();
        return view('livewire.product-comments',['comments'=>$comments]);
    }

    public function addComment(){
        ProductComment::create([
            'product_id'=>1,
            'comment'=>$this->comment
        ]);
        $this->reset(['comment']);
    }
}
