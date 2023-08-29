<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Tropical extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>'Yes, bring on the jungle vibes 🌴',
            'value'=>1
        ],
        [
            'label'=>"I'll pass on the exotics, thanks ❌",
            'value'=>0
        ]
    ];

    public function survey_title(): string
    {
        return __('Do you want something tropical?');
    }

    public function name(): string
    {
        return __('tropical');
    }

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'tropical'=> $this->model->tropical
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
                'state.tropical'     => ['required'],
            ],
            [],
            [
                'state.tropical'     => __('tropical'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Tropical');
    }

}