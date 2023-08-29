<?php

namespace App\Http\Livewire\Opportunity;

use Livewire\Component;
use WireUi\Traits\Actions;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class ContactPopup extends Component
{
    use Actions;
    public $title;
    public $filterFormVisable = false;
    public $email;
    public $button_label;

    public function open(){
        $this->filterFormVisable = true;
    }

    public function close(){
        $this->filterFormVisable = false;
    }

    public function submit(){

        $this->validate([
            'email' => 'required|email|max:255'
        ]);

        try{
                
            $data = [
                'email'=>$this->email,
                'title'=>$this->title,
                'message'=>'Request Plant Database Search Finder Implementation'
            ];
            
            Mail::to(env('ALTERNATIVE_MAIL_CONTACT_ADDRESS'))->send(new SendMail($data));
            
            $this->filterFormVisable = false;

            return $this->notification()->success(
                $title = 'Request Sent',
                $description = 'Thank you, we will be contacting you soon'
            );

            return back()->with('success','Thank you for contacting us');
                
        }catch(\Exception $e){

            return $this->notification()->error(
                $title = 'Ran into a problem',
                $description = 'Please refresh or try again later'
            );

        }

    }

    public function render()
    {
        return view('livewire.opportunity.contact-popup');
    }
}
