<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Medicinal extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>'I want plants that have superpowers against sickness 🤒',
            'value'=>1
        ],
        [
            'label'=>"I don't want no plant magic ❌",
            'value'=>0
        ]
    ];

    public function survey_title(): string
    {
        return __("Do you want a plant with medicinal properties?");
    }

    public function name(): string
    {
        return __('medicinal');
    }
    
    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'medicinal'=> $this->model->medicinal
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
                'state.medicinal'     => ['required'],
            ],
            [],
            [
                'state.medicinal'     => __('Medicinal'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Medicinal');
    }

}