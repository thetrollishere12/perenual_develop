<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Watering extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';
    
    public $array = [
        [
            'label'=>'Yes I got all the time in the world 🌎',
            'value'=>'frequent'
        ],
        [
            'label'=>'I might have a minute or two!',
            'value'=>'average'
        ],
        [
            'label'=>'I can barely sit down 😩',
            'value'=>'minimum'
        ]
    ];

    public function survey_title(): string
    {
        return __('Are you a person that has time to water?');
    }

    public function name(): string
    {
        return __('watering');
    }

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