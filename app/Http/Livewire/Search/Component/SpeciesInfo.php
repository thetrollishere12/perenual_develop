<?php

namespace App\Http\Livewire\Search\Component;

use Livewire\Component;

use App\Models\Comment\SpeciesComment;
use App\Models\Comment\SpeciesCommentReview;
use Auth;

use WireUi\Traits\Actions;
class SpeciesInfo extends Component
{
    use Actions;

    public $queries;
    
    public $comment;

    public $addReviewForm = false;

    public $star;

    public $valid = true;

    public function mount(){
        $this->star  = 0;

        if(Auth::user()){

            $comment = SpeciesCommentReview::where('species_id',$this->queries['id'])->where('user_id',auth()->user()->id)->get();

            if ($comment->count() > 0) {
                $this->valid = false;
            }

        }

    }

    public function stars($number){

        $this->star = $number;

    }

    public function addCommentReview(){

        if (!Auth::user()) {
        
            $this->dialog([
                'title' => 'Please Login',
                'description' => 'You must login to leave your review',
                'icon' => 'info'
            ]);

        }else{
            $this->addReviewForm = true;
        }

    }

    public function submit(){

        $this->validate([
            'star.*.stars' => 'required|numeric|min:1|max:5',
            'comment' => 'string|nullable'
        ]);

        try{

            // $rating = new Rating;
            // $rating->user_id = Auth::id();
            // $rating->store_id = $this->order['store_id'];
            // $rating->number = $this->order['number'];
            // $rating->sku = $order['sku'];
            // $rating->ratings = $order['stars'];
            // $rating->comment = $order['comment'];
            // $rating->save();

            $comment = SpeciesComment::create([
                'species_id'=>$this->queries['id'],
                'comment'=>$this->comment,
                'user_id'=>auth()->user()->id,
            ]);

            SpeciesCommentReview::create([
                'user_id'=>auth()->user()->id,
                'species_comment_id'=>$comment->id,
                'ratings'=>$this->star,
                'species_id'=>$this->queries['id']
            ]);

            $this->notification([
                'title'       => 'Review submitted!',
                'description' => 'You have successfully submitted your review',
                'icon'        => 'success',
            ]);

            $this->valid = false;

        }catch(\Exception $e){
            // dd($e);
            $this->notification([
                'title'       => 'There was an error!',
                'description' => $e->getMessage(),
                'icon'        => 'error',
            ]);

        }

        return $this->addReviewForm = false;

    }

    public function render()
    {   

        $this->queries->species_rating = $this->queries->c_ratings();

        return view('livewire.search.component.species-info');
    }
}
