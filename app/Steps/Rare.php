<?php

namespace App\Steps;

use App\Models\Analyze;
use Vildanbina\LivewireWizard\Components\Step;

class Rare extends Step
{
    // Step view located at resources/views/steps/general.blade.php 
    protected string $view = 'steps.rare';

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
     * When Wizard Form has submitted
     */
    public function save($state)
    {
    
        $data=Analyze::updateOrCreate([
            'id'=>$this->model->id
        ],$state);

        return redirect()->to('result')->with('analyze',$data);
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