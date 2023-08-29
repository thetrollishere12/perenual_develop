<?php

namespace App\Http\Livewire\Admin\Merchant;

use Livewire\Component;
use App\Mail\MerchantEmailMarketing;
use Illuminate\Support\Facades\Mail;
use App\Models\Admin\Merchant\MerchantEmailSenderResult;
use Auth;
use WireUi\Traits\Actions;
class SendEmail extends Component
{
    use Actions;
    public $comment;
    public $email;
    public $title;



    public function mount(){

        $this->title = 'Looking for help and to help';
        $this->comment = "Howdy, hope you’re doing well! I’m a university student trying to launch an online Saas tool to help plants/plant related businesses and was wondering if you would be interested in trying it? It's a survey widget that helps customers find the best fit for them based of your products and a way to understand your customers better. Let me know if you're interested so I can help! Here is a sample of how it works and its free to intergrate! - https://perenual.com/plant-survey-quiz-test/find-my-houseplant-survey";

        // $this->comment = "Howdy, hope you’re doing well! I’m a university student trying to launch an online marketplace for plants/plant related and was wondering if you would be interested in trying it out by listing some of your products on it? Let me know if you're interested so I can help! The site is https://perenual.com";

    }

    public function save(){

        $this->validate([
            'email' => 'required|string|email',
            'title' => 'required|string',
            'comment' =>'required|string'
        ]);

        $data = [
            'title'=> $this->title,
            'comment' => $this->comment
        ];

        $attribute = [
            'source' => 'Google'
        ];

        MerchantEmailSenderResult::firstOrCreate(
            ['email' => $this->email],
            [
                'sender_id' => Auth::user()->id,
                'email' => $this->email,
                'attribute' => $attribute
            ]
        );

        Mail::to($this->email)->send(new MerchantEmailMarketing($data));

        $this->notification([
            'title'       => 'Email Sent!',
            'description' => 'Email was sent to '.$this->email,
            'icon'        => 'success'
        ]);

        return  $this->reset(['email']);

    }

    public function render()
    {
        return view('livewire.admin.merchant.send-email');
    }
}
