<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationNumber extends Component
{
    public function render()
    {

        $notifications = auth()->user()->unreadNotifications;

        return view('livewire.notification-number',['notifications'=>$notifications]);
    }
}
