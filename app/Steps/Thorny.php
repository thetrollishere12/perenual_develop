<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Thorny extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>"I like it thorny 😈",
            'value'=>1
        ],
        [
            'label'=>"No thorns please ❌",
            'value'=>0
        ]
    ];

    public function survey_title(): string
    {
        return __('Do want thorns on your plant?');
    }

    public function name(): string
    {
        return __('thorny');
    }

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'thorny'=> $this->model->thorny
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
                'state.thorny'     => ['required'],
            ],
            [],
            [
                'state.thorny'     => __('thorny'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Thorny');
    }

}