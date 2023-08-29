<?php

namespace App\Steps;

use App\Models\Survey;
use Vildanbina\LivewireWizard\Components\Step;

class Rare extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'survey.steps.component.array';

    public $array = [
        [
            'label'=>'Yes I do ✨',
            'value'=>1
        ],
        [
            'label'=>'No thanks ❌',
            'value'=>0
        ]
    ];

    public function survey_title(): string
    {
        return __('Do you want something rare?');
    }

    public function name(): string
    {
        return __('rare');
    }

    /*
     * Initialize step fields
     */
    public function mount()
    {
        $this->mergeState([
            'rare'=> $this->model->rare
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
                'state.rare'     => ['required'],
            ],
            [],
            [
                'state.rare'     => __('Rare'),
            ],
        ];
    }

    /*
     * Step Title
     */
    public function title(): string
    {
        return __('Rare');
    }

}