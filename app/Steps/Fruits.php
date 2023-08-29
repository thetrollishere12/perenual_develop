<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Fruits extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>'Yes, I want a plant that drops goodies 🍒🍎🍈',
            'value'=>1
        ],
        [
            'label'=>'No goodies ❌',
            'value'=>0
        ]
    ];

    public function survey_title(): string
    {
        return __('Would you like some fruits?');
    }
    
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
                'state.fruits'     => __('fruits'),
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

    public function name(): string
    {
        return __('fruits');
    }
    
}