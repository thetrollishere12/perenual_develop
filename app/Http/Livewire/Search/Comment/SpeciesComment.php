<?php

namespace App\Http\Livewire\Search\Comment;

use Livewire\Component;
use App\Models\Comment\SpeciesComment as SpeciesComments;
use WireUi\Traits\Actions;
use Auth;

class SpeciesComment extends Component
{
    use Actions;
    public $species_id;
    public $comment;
    public $show_child;
    public $likes = 0;
    public $showCommentBox=false;
    public $show_edit=false;
    public $editComment;
    public $postComment;

    public function mount($comment=null,$species_id){
        // dd($comment);
        $this->species_id=$species_id;
        $this->comment=$comment;
    }

    public function render()
    {
        return view('livewire.search.comment.species-comment');
    }

    public function countReplies(){
        return SpeciesComments::where('parent_id',$this->comment->id)->count();
    }

    public function addComment(){

        $this->validate([
            'postComment'=>'required'
        ]);

        SpeciesComments::create([
            'species_id'=>$this->species_id,
            'comment'=>$this->postComment,
            'parent_id'=>$this->comment->id,
            'user_id'=>auth()->user()->id
        ]);
        
        $this->postComment='';
        $this->show_child=true;

        $this->notification([
            'title'       => 'Comment submitted!',
            'description' => 'You have successfully made a comment',
            'icon'        => 'success',
        ]);

    }

    public function removeComment(){
        $comment=SpeciesComments::where('id',$this->comment->id)->where('user_id',auth()->user()->id)->first();
        $comment->delete();

        $this->notification([
            'title'       => 'Deleted!',
            'description' => 'Your comment has been deleted',
            'icon'        => 'x-circle',
            'iconColor'   => 'text-negative-400'
        ]);

        $this->emit('commentDeleted');
    }

    public function showEdit(){
        $this->show_edit=true;
        $this->editComment=$this->comment->comment;
    }

    public function updateComment(){
        $this->validate([
            'editComment'=>'required'
        ]);
        SpeciesComments::where('id',$this->comment->id)->update([
            'comment'=>$this->editComment
        ]);
        $this->show_edit=false;
        $this->editComment='';
        $this->comment=SpeciesComments::find($this->comment->id);
    }

    public function like(){
        // adding likes
        if(Auth::check()){

            if($this->comment->user_id != Auth::user()->id){

                $array = json_decode($this->comment->user_like);

                array_push($array,Auth::user()->id);
            
                SpeciesComments::where('id',$this->comment->id)->update([
                    'user_like'=>array_unique($array)
                ]);

            }else{

            return $this->notification([
                'title'       => 'Cannot Like!',
                'description' => 'Cannot like your own comment',
                'icon'        => 'x-circle',
                'iconColor'   => 'text-negative-400'
            ]);

            }   

        }else{

            $this->notification([
                'title'       => 'Please Login!',
                'description' => 'Please login to like the comment',
                'icon'        => 'x-circle',
                'iconColor'   => 'text-negative-400'
            ]);

        }
    }

}
