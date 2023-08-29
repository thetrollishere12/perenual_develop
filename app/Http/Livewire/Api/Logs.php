<?php

namespace App\Http\Livewire\Api;

use Livewire\Component;
use App\Models\ApiLog;
use WireUi\Traits\Actions;
class Logs extends Component
{
    use Actions;
    public $logs;

    public function mount(){

        $this->logs[] = [
            'type'=>'Note',
            'title'=>'',
            'message'=>''
        ];

    }

    public function new(){

        $this->logs[] = [
            'type'=>'Note',
            'title'=>'',
            'message'=>''
        ];

    }

    public function delete_log($id){

        ApiLog::find($id)->delete();

        return $this->notification([
            'title'       => 'Log was deleted!',
            'description' => 'The log was successfully deleted',
            'icon'        => 'error'
        ]);

    }

    public function delete($l){

        unset($this->logs[$l]);

    }

    public function submit(){

        ApiLog::create([
            'message'=>$this->logs
        ]);

        $this->logs = [];
        $this->logs[] = [
            'type'=>'Note',
            'title'=>'',
            'message'=>''
        ];

        return $this->notification([
            'title'       => 'Logs  was saved!',
            'description' => 'The log was successfully saved',
            'icon'        => 'success'
        ]);

    }

    public function render()
    {
        return view('livewire.api.logs',[
            'logging'=>ApiLog::latest()->get()
        ]);
    }
}
