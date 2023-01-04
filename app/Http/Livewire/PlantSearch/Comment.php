<?php

namespace App\Http\Livewire\PlantSearch;

use Livewire\Component;
use App\Models\ProductComment;
use App\Models\ProductReview;

class Comment extends Component
{
    protected $listeners=['commentDeleted'=>'render'];

    public $parent_id;
    public $product_id;
    public $comment;

    public $ratings,$second_comment;

    public function mount($product_id,$parent_id=null){
        $this->product_id = $product_id;
        $this->parent_id = $parent_id;
    }
    public function render()
    {
        $comments=ProductComment::with('productCommentsLikes','productReviews')->where('product_id',$this->product_id)->where('parent_id',$this->parent_id)->get();

        // check if user have already gave review

        $review_check=ProductReview::where('product_id',$this->product_id)->where('user_id',auth()->user()->id)->first();
        return view('livewire.plant-search.comment',['comments'=>$comments,'review_check'=>$review_check]);
    }

    public function addComment(){
        $this->validate([
            'comment'=>['required']
        ]);
        ProductComment::create([
            'product_id'=>$this->product_id,
            'comment'=>$this->comment,
            'user_id'=>auth()->user()->id
        ]);
        $this->reset(['comment']);
    }

    public function addCommentRatings(){
        $this->validate([
            'ratings'=>['required','numeric'],
            'second_comment'=>['required']
        ]);

        $pc=ProductComment::create([
            'product_id'=>$this->product_id,
            'comment'=>$this->second_comment,
            'user_id'=>auth()->user()->id,
        ]);

        ProductReview::create([
            'ratings'=>$this->ratings,
            'user_id'=>auth()->user()->id,
            'product_comment_id'=>$pc->id,
            'product_id'=>$this->product_id
        ]);
        $this->reset(['ratings','second_comment']);
    }
}
