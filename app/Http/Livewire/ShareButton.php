<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShareButton extends Component
{

    public $shareModal = false;
    public $url;
    public $body;
    public $title;

    public function open(){
        $this->shareModal = true;
    }

    public function render()
    {
        return view('livewire.share-button');
    }
}
