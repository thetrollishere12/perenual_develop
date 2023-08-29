<?php

namespace App\Http\Livewire\Search\Component;

use Livewire\Component;

class RelatedArticle extends Component
{

    public $queries;
    
    public function render()
    {
        return view('livewire.search.component.related-article');
    }
}
