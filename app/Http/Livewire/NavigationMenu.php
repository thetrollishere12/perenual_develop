<?php

namespace App\Http\Livewire;

use Storage;
use Livewire\Component;
use Laravel\Jetstream\Http\Livewire\NavigationMenu as JetstreamNavigationMenu;

class NavigationMenu extends JetstreamNavigationMenu
{
    protected $listeners = [
        'refresh-navigation-menu' => '$refresh',
    ];

    public function mount(){
        // Assuming you have some logic to fetch and decode the JSON
        $this->json = json_decode(Storage::disk('local')->get('json/navigation-menu.json'), true);
    }

    public function render()
    {
        return view('navigation-menu',['json'=>$this->json]);
    }
}