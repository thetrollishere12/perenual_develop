<?php

namespace App\Steps;

use App\Models\Survey;
use Vildanbina\LivewireWizard\Components\Step;

class Start extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.general.start-side';

    /*
     * Initialize step fields
     */

    public $h1;
    public $p;

    public function mount()
    {

        $this->h1 = $this->livewire->h1;

        $this->p = $this->livewire->p;

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
        return __('Start');
    }

}