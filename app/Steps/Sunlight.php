<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Sunlight extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'steps.sunlight';

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'sunlight'=> $this->model->sunlight
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
                'state.sunlight'     => ['required'],
            ],
            [],
            [
                'state.sunlight'     => __('Sunlight'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Sunlight');
    }
}