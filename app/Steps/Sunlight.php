<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Sunlight extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';


    public $array = [
        [
            'label'=>"I'm drowning in sunlight 😎",
            'value'=>'full sun'
        ],
        [
            'label'=>'I get it then and there',
            'value'=>'part shade'
        ],
        [
            'label'=>"What's sunlight?! 🤔",
            'value'=>'shade'
        ]
    ];

    public function survey_title(): string
    {
        return __('How much sunlight do you get?');
    }

    public function name(): string
    {
        return __('sunlight');
    }

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
                'state.sunlight'     => __('sunlight'),
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