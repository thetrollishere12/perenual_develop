<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Cuisine extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>'I like to herb up my meals 👨‍🍳',
            'value'=>'yes'
        ],
        [
            'label'=>'No thanks ❌',
            'value'=>'no'
        ]
        // [
        //     'label'=>'I prefer my food to be herbivore-free.',
        //     'value'=>'no'
        // ]
    ];

    public function survey_title(): string
    {
        return __('Do you want to use plants in your cooking?');
    }

    public function name(): string
    {
        return __('cuisine');
    }

    /*
     * Initialize step fields
     */
    public function mount()
    {   
        $this->mergeState([
            'cuisine'=> $this->model->cuisine
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
                'state.cuisine'     => ['required'],
            ],
            [],
            [
                'state.cuisine'     => __('cuisine'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Cuisine');
    }

}