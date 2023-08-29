<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Indoor extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.indoor';

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'indoor'=> $this->model->indoor,
            'location'=>$this->model->location
        ]);
    }
    
    /*
    * Step icon 
    */
    public function icon(): string
    {
        return 'check';
    }


    /*
     * Step Validation
     */
    public function validate()
    {
        return [
            [
                'state.indoor'     => ['required'],
                'state.location'  =>[($this->livewire->state['indoor']==="0")?'required':'nullable']
            ],
            [],
            [
                'state.indoor'     => __('Indoor'),
                'state.location'  => __('Location')
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Indoor');
    }

    public function updatedState(){
        if($this->livewire->state['indoor']==="1"){
            $this->livewire->state['location']=null;
        }
    }
}   