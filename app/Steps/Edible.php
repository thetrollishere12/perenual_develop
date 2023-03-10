<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Edible extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'steps.edible';

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'edible'=> $this->model->edible
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
                'state.edible'     => ['required'],
            ],
            [],
            [
                'state.edible'     => __('Edible'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Edible');
    }
}