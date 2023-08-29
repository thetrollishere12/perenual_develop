<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Cycle extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>"I want a plant that's a botanical phoenix, reblooming each year 🌲🐦",
            'value'=>'perennial'
        ],
        [
            'label'=>'One life is enough for plants ❌',
            'value'=>'annual'
        ]
    ];

    public function survey_title(): string
    {
        return __('Are you looking for a plant to come back to life?');
    }

    public function name(): string
    {
        return __('cycle');
    }

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'cycle'=> $this->model->cycle
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
                'state.cycle'     => ['required'],
            ],
            [],
            [
                'state.cycle'     => __('cycle'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Cycle');
    }
}