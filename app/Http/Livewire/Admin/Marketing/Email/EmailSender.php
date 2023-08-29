<?php

namespace App\Http\Livewire\Admin\Marketing\Email;

use Livewire\Component;
use Auth;
use WireUi\Traits\Actions;
use Illuminate\Support\Facades\Mail;
use App\Models\ApiCredentialKey;
use App\Mail\MerchantEmailMarketing;

class EmailSender extends Component
{

    use Actions;
    public $comment;
    public $email;
    public $title;
    public $emails = [];

    public $min = 0.5;
    public $max = 2;

    public $sent = 0;

    public function mount(){

        $this->title = 'Update on API';
        $this->comment = "Howdy, hope you’re doing well! If you already received this email, please ignore it

I recently updated our API data for many sections like propagation, hardiness zones and other areas. I'm thinking of phasing out fruiting season so just a heads up unless you find it useful plz let me know.

I have also created a log page where you can keep up to date to what im doing with the api - https://perenual.com/docs/api/logs

If you'd like to provide feedback, please feel free to reply to this email and share your thoughts.I will update again in a 1-2 months about the next update.

Thank you! - the guy that made the site😀";

        $keys = ApiCredentialKey::all();

        foreach($keys as $k => $key) {
            if(!$key->user()->first()->paypal_id){
                $this->emails[] = $key->user()->first()->email;
            }
            
        }
        
        // for ($i = 1; $i <= 210; $i++){  
        //     unset($this->emails[$i]);
        // }
      

    }

    public function save(){

        $this->validate([
            'title' => 'required|string',
            'comment' =>'required|string'
        ]);

        $data = [
            'title'=> $this->title,
            'comment' => $this->comment
        ];
     
        foreach ($this->emails as $email) {
            
            try{
            
                Mail::to($email)->send(new MerchantEmailMarketing($data));

                $this->sent++;
                
                sleep(rand($this->min,$this->max));

                $this->notification([
                    'title'       => 'Emal Sent!',
                    'description' => 'Email was sent to '.$email,
                    'icon'        => 'success'
                ]);
    
            }catch(\exception $e){
                continue;
            }
    
        }

        $this->notification([
            'title'       => 'Emal Sent!',
            'description' => 'Email was sent',
            'icon'        => 'success'
        ]);

        return  $this->reset(['email']);

    }

    public function render()
    {
        return view('livewire.admin.marketing.email.email-sender');
    }
}