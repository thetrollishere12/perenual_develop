<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class CareLevel extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>"I'm basically a Botanical Connoisseur 🧐",
            'value'=>'Moderate'
        ],
        // [
        //     'label'=>"I have enough plant knowledge to keep a few plants alive 🌱",
        //     'value'=>'Medium'
        // ],
        [
            'label'=>"I can keep a few plants alive 🌱",
            'value'=>'Medium'
        ],
        [   
            'label'=>"I have a PhD in plant demise 🎓",
            'value'=>"Low"
        ]
    ];

    public function survey_title(): string
    {
        return __('How experienced are you with plants?');
    }

    public function name(): string
    {
        return __('care_level');
    }

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'care_level'=> $this->model->care_level
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
                'state.care_level'     => ['required'],
            ],
            [],
            [
                'state.care_level'     => __('care level'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Care Level');
    }

}