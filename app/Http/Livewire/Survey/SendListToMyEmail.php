<?php

namespace App\Http\Livewire\Survey;

use Livewire\Component;
use WireUi\Traits\Actions;

class SendListToMyEmail extends Component
{

    use Actions;
    public $filterFormVisable = false;
    public $email;

    public function email()
    {
        $this->filterFormVisable = false;

        $this->notification([
            'title'       => 'Success',
            'description' => 'We will be sending you your care tips & guides!',
            'icon'        => 'success',
        ]);

    }

    public function render()
    {
        return view('livewire.survey.send-list-to-my-email');
    }
}
