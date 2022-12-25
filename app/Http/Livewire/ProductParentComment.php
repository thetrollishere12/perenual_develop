<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ProductComment;
use App\Models\ProductCommentsLikes;

class ProductParentComment extends Component
{
    public $comment,$show_child,$postComment;
    public $showCommentBox=false;
    public $likes,$show_edit=false;
    public $editComment;

    public function mount($comment=null){
        $this->comment=$comment;
        $this->likes=$this->comment->productCommentsLikes()->count();
        
    }
    public function render()
    {
       return view('livewire.product-parent-comment');
    }

    public function countReplies(){
        return ProductComment::where('parent_id',$this->comment->id)->count();
    }
    
    public function addComment(){
        ProductComment::create([
            'product_id'=>1,
            'comment'=>$this->postComment,
            'parent_id'=>$this->comment->id,
            'user_id'=>auth()->user()->id
        ]);
        
        $this->postComment='';
        $this->show_child=false;
    }

    public function like(){
        // adding likes
        ProductCommentsLikes::create([
            'product_comment_id'=>$this->comment->id,
            'likes'=>1,
            'user_id'=>auth()->user()->id
        ]);
        $this->likes=$this->comment->productCommentsLikes()->count();
    }

    public function removeComment(){
        $comment=ProductComment::where('id',$this->comment->id)->where('user_id',auth()->user()->id);
        $comment_value=$comment->first();
        // check if the comment is parent or not 
        // if parent delete its child also
        if($comment_value->parent_id==NULL){
            // get all the child comments
            ProductComment::where('parent_id',$comment_value->id)->delete();
        }
        $comment->delete();
        $this->emit('commentDeleted');
    }

    public function showEdit(){
        $this->show_edit=true;
        $this->editComment=$this->comment->comment;
    }

    public function updateComment(){
        ProductComment::where('id',$this->comment->id)->update([
            'comment'=>$this->editComment
        ]);
        $this->emit('commentDeleted');
    }

}
