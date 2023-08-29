<?php

namespace App\Http\Livewire\Admin\Marketing\Instagram;

use Livewire\Component;

use App\Models\Admin\Comment\PostComment;
use WireUi\Traits\Actions;

use Carbon\Carbon;

class Comment extends Component
{   
    use Actions;
    public $comments;
    public $comment;
    public $type;
    public $req;
    public $count;

    protected $listeners = ['copied' => 'copied','refreshComponent' => '$refresh'];

    public function copied($id){

        PostComment::where('id',$id)->increment('copied');
        $this->emit('refreshComponent');
    }

    public function mount(){

        // PostComment::where('type','["plants","general"]')->update([
        //     "type"=>'["plants"]'
        // ]);

        if (isset($this->req['type']) && count(explode(',',$this->req['type'])) > 0) {

            $types = $this->req['type'];

            $this->comments = PostComment::inRandomOrder()->where(function($q) use($types){

                foreach (explode(',',$types) as $type) {
                    $q->where('type','LIKE','%'.$type.'%');
                }

            })->limit(120)->get();
        }else{
            $this->comments = PostComment::inRandomOrder()->limit(120)->get();
        }

    }

    public function emoji($c_type){

        $output = "";
        $amount = rand(1,2);

        if ($c_type=="general") {
            
            $array = ["😍","🥰","🤩","😀","💓","💚","🤗","😉","🤍"];

            for ($i=0; $i < $amount; $i++) { 
                $output .= $array[rand(0,count($array)-1)];
            }

        }elseif($c_type=="funny"){

            $array = ["🤣","😂","😅","😁","😆"];

            for ($i=0; $i < $amount; $i++) { 
                $output .= $array[rand(0,count($array)-1)];
            }

        }elseif($c_type=="plants" || $c_type=="question marketing 1"){

            $array = ["🌿","🍃","🌱","😍","🥰","🤩","😀","💓","💚","🤍","🤗","😉"];

            for ($i=0; $i < $amount; $i++) { 
                $output .= $array[rand(0,count($array)-1)];
            }

        }elseif($c_type=="reply marketing 1"){

            $array = ["🥰","😀","💓","💚","🤍","🤗","😉","😄"];

            for ($i=0; $i < $amount; $i++) { 
                $output .= $array[rand(0,count($array)-1)];
            }

        }

        if (isset($this->req['extension'])) {
            return $output." ".ucfirst($this->req['extension']);
        }else{
            return $output;
        }

    }

    public function add(){

        $this->validate([
            'comment' => 'required|string|min:5|max:300',
            'type' => 'required|array'
        ]);

        PostComment::firstOrCreate([
            'comment'=>$this->comment
        ],[
            'type'=>$this->type,
            'comment'=>trim($this->comment)
        ]);

        $this->reset(['comment']);

        return $this->notification([
            'title'       => 'Comment Added',
            'description' => 'Comment was added to the list',
            'icon'        => 'success'
        ]);

    }

    public function render()
    {

        foreach ($this->comments as $key => $comment) {
            
            $comment['comment'] .= $this->emoji($comment['type'][0]);

        }

        $this->count = PostComment::where('updated_at', '>', Carbon::now()->subHours(2)->toDateTimeString())->get()->count();

        return view('livewire.admin.marketing.instagram.comment');
    }
}
