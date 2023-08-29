<?php

namespace App\Http\Livewire\Navigation;

use Livewire\Component;

class NotificationDropdown extends Component
{

    public function read($id){
        auth()->user()->notifications->where('id', $id)->markAsRead();
    }

    public function readAll(){
        auth()->user()->notifications->markAsRead();
    }
    
    public function render()
    {

        $notifications = auth()->user()->unreadNotifications;

        foreach($notifications as $notification){

            switch ($notification->data['type']) {
                case 'sold':
                    $notification->output = get_order_products($notification->data['order_number']);
                    break;
                default:
                    break;
            }

        }

        return view('livewire.navigation.notification-dropdown',['notifications'=>$notifications]);
    }
}
