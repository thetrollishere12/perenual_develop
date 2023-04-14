<?php

namespace App\Http\Livewire;

use App\Steps\Indoor;
use App\Models\Analyze;
use App\Steps\Watering;
use App\Steps\Flower;
use App\Steps\Sunlight;
use App\Steps\Fruits;
use App\Steps\Edible;
use App\Steps\Rare;
use Vildanbina\LivewireWizard\WizardComponent;

class AnalysisWizard extends WizardComponent
{
    public $analyzeId;

    public array $steps = [
        Indoor::class,
        Watering::class,
        Flower::class,
        Sunlight::class,
        Fruits::class,
        Edible::class,
        Rare::class
    ];

    public function model()
    {
        return Analyze::findOrNew($this->analyzeId);
    }

}
