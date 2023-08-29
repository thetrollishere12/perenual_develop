<?php

namespace App\Http\Livewire\Search\Comment;

use Livewire\Component;
use App\Models\Comment\SpeciesComment;
use App\Models\Comment\SpeciesCommentReview;
use App\Models\User;
use WireUi\Traits\Actions;

class Species extends Component
{
    use Actions;
    protected $listeners=['commentDeleted'=>'render','refreshComponent' => '$refresh'];

    public $parent_id;
    public $species_id;
    public $comment;

    public function mount($species_id,$parent_id=null){
        $this->species_id = $species_id;
        $this->parent_id = $parent_id;
    }

    public function render()
    {

        $this->comments = SpeciesComment::with('SpeciesCommentReview','user')
        ->where('species_id',$this->species_id)
        ->where('parent_id',$this->parent_id)->get();
        
        // check if user have already gave review

        // $this->review_check = SpeciesCommentReview::whereIn('scientific_name',$this->queries['scientific_name'])->where('user_id',auth()->user()->id)->first();

        return view('livewire.search.comment.species');
    }

    public function addComment(){

        $this->validate([
            'comment'=>'required|string|max:5000'
        ]);

        SpeciesComment::create([
            'species_id'=>$this->species_id,
            'comment'=>$this->comment,
            'user_id'=>auth()->user()->id
        ]);

        $this->notification([
            'title'       => 'Comment Added!',
            'description' => 'Your comment has been added',
            'icon'        => 'success',
        ]);

        $this->reset('comment');
        $this->emit('refreshComponent');
    }

}
