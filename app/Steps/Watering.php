<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Watering extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'steps.watering';

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'watering'=> $this->model->watering
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
                'state.watering'     => ['required'],
            ],
            [],
            [
                'state.watering'     => __('Watering'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Watering');
    }
}