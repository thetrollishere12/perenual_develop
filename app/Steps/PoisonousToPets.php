<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class PoisonousToPets extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>'Yes, they love to munch 😱',
            'value'=>1
        ],
        [
            'label'=>"No plant eaters ❌",
            'value'=>0
        ]
    ];

    public function survey_title(): string
    {
        return __("Do you have pets that chew on plants?");
    }

    public function name(): string
    {
        return __('poisonous_to_pets');
    }
    
    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'poisonous_to_pets'=> $this->model->poisonous_to_pets
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
                'state.poisonous_to_pets'     => ['required'],
            ],
            [],
            [
                'state.poisonous_to_pets'     => __('Pets'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Pets');
    }

}