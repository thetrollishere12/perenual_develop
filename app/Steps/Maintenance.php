<?php

namespace App\Steps;

use Vildanbina\LivewireWizard\Components\Step;

class Maintenance extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>'I got 25/8',
            'value'=>'Moderate'
        ],
        [
            'label'=>'I got some time to spare.',
            'value'=>'Medium'
        ],
        [
            'label'=>'I have no time, not even for a one-second nap 😔',
            'value'=>'Low'
        ]
    ];

    public function survey_title(): string
    {
        return __('Do you have time to spend with your plants?');
    }

    public function name(): string
    {
        return __('maintenance');
    }

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'maintenance'=> $this->model->maintenance
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
                'state.maintenance'     => ['required'],
            ],
            [],
            [
                'state.maintenance'     => __('Maintenance'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Maintenance');
    }
}