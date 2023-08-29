<?php

namespace App\Http\Livewire\Navigation;

use Livewire\Component;

class NotificationNumber extends Component
{
    public function render()
    {
        $notifications = auth()->user()->unreadNotifications;
        return view('livewire.navigation.notification-number',['notifications'=>$notifications]);
    }
}
