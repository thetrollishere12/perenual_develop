<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

use App\Models\Survey;

class Submit extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.general.submit';

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'email'=> $this->model->email,
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

        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Submit');
    }

    public function name(): string
    {
        return __('submit');
    }

}