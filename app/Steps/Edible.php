<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Edible extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>'Yes 😋🤤',
            'value'=>1
        ],
        [
            'label'=>'No thanks 🤢',
            'value'=>0
        ]
    ];

    public function survey_title(): string
    {
        return __('Do you want a plant you can eat from?');
    }

    public function name(): string
    {
        return __('edible');
    }

    /*
     * Initialize step fields
     */
    public function mount()
    {   
        $this->mergeState([
            'edible'=> $this->model->edible
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
                'state.edible'     => ['required'],
            ],
            [],
            [
                'state.edible'     => __('Edible'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Edible');
    }

}