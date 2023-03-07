<?php

namespace App\Http\Livewire\Profile\User;

use Livewire\Component;
use App\Models\ApiCredentialKey;
use Auth;
use WireUi\Traits\Actions;

class Developer extends Component
{

    public $credential;
    use Actions;


    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(){

        $this->credential = ApiCredentialKey::where('user_id',Auth::user()->id)->first();

    }

    public function request(){

        try{

        $key = random_id('sk-');

        ApiCredentialKey::updateOrCreate(
            [
                'user_id' => Auth::user()->id
            ],
            [
                'key' => $key
            ]
        );

        $this->dialog([
            'title' => 'Successfully Generated. Please copy it.',
            'description'=> $key,
            'icon' => 'success',
            // close: 'b',
            "close" => [
                "label" => 'Done',
                "positive" => true
            ]
        ]);

        }catch(\Exception $e){

            $this->notification([
                'title'       => 'There Was An Error',
                'description' => 'There was an error trying to generate a key. Please try again or contact us',
                'icon'        => 'error',
            ]);

        }

        $this->emit('refreshComponent');

    }

    public function render()
    {
        return view('livewire.profile.user.developer');
    }
}
