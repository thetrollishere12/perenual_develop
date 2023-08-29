<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Flower extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>'Yes, I want a confetti blooms 🌸',
            'value'=>1
        ],
        [
            'label'=>'No flowers, please ❌',
            'value'=>0
        ]
    ];
    
    public function survey_title(): string
    {
        return __('Woud you like some flowers?');
    }

    public function name(): string
    {
        return __('flower');
    }

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'flower'=> $this->model->flower
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
                'state.flower'     => ['required'],
            ],
            [],
            [
                'state.flower'     => __('flower'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Flower');
    }

}