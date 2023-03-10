<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Flower extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'steps.flower';

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'flower'=> $this->model->flower
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
                'state.flower'     => ['required'],
            ],
            [],
            [
                'state.flower'     => __('Flower'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Flower');
    }
}