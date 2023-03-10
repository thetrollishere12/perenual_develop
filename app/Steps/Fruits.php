<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Fruits extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'steps.fruits';

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'fruits'=> $this->model->fruits
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
                'state.fruits'     => ['required'],
            ],
            [],
            [
                'state.fruits'     => __('Fruits'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Fruits');
    }
}