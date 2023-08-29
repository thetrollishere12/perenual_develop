<?php

namespace App\Http\Livewire\Admin\User;

use Livewire\Component;
use App\Models\User;
use Storage;

class Users extends Component
{   

    public $users;

    public function mount(){

        $this->users = User::all();

        foreach($this->users as $key => $user){

            if ($user->api_key()->count() == 0) {
                unset($this->users[$key]);
            }

        }

    }

    public function export(){

        $fp = fopen(Storage::disk('local')->path("livewire-tmp/export-user.csv"),'w');

        $columns = array('id','name','email','created_at','updated_at');
        fputcsv($fp,$columns);

        foreach($this->users as $key => $user){

            $columns = array($user->id,$user->name,$user->email,$user->created_at,$user->updated_at);
            fputcsv($fp,$columns);

        }
        fclose($fp);

        return Storage::disk('local')->download('livewire-tmp/export-user.csv');

    }

    public function render()
    {   
        
        return view('livewire.admin.user.users');

    }
}
