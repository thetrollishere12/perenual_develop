<?php

namespace App\Http\Livewire\Admin\Species\Nutrition;

use Livewire\Component;

use Carbon\Carbon;

use App\Models\Species;
use App\Models\SpeciesNutritionFact;
use WireUi\Traits\Actions;

use Auth;
use Illuminate\Support\Facades\Validator;

class NutritionInput extends Component
{

    use Actions;

    public $s_id;

    public $speciesNutrition;

    public $speciesNutritions;

    public $species;

    public $nutrition;

    public function addNutrition($n_id){

            // Populate the properties

            if ($n_id) {
  
                $fact = SpeciesNutritionFact::find($n_id);

                $this->nutrition = [
                    'species_id'=>$this->s_id,
                    'user_id'=>Auth::user()->id,
                    'plant_part'=>$fact->plant_part,
                    'serving_size' => $fact->serving_size ?? null,
                    'calories' => $fact->calories ?? null,
                    'total_fat' => $fact->total_fat ?? null,
                    'saturated_fat' => $fact->saturated_fat ?? null,
                    'trans_fat' => $fact->trans_fat ?? null,
                    'monounsaturated_fat' => $fact->monounsaturated_fat ?? null,
                    'polyunsaturated_fat' => $fact->polyunsaturated_fat ?? null,
                    'cholesterol' => $fact->cholesterol ?? null,
                    'sodium' => $fact->sodium ?? null,
                    'total_carbohydrate' => $fact->total_carbohydrate ?? null,
                    'dietary_fiber' => $fact->dietary_fiber ?? null,
                    'sugars' => $fact->sugars ?? null,
                    'protein' => $fact->protein ?? null,
                    'vitamin_d' => $fact->vitamin_d ?? null,
                    'calcium' => $fact->calcium ?? null,
                    'iron' => $fact->iron ?? null,
                    'potassium' => $fact->potassium ?? null,
                    'vitamin_a' => $fact->vitamin_a ?? null,
                    'vitamin_c' => $fact->vitamin_c ?? null,
                    'vitamin_e' => $fact->vitamin_e ?? null,
                    'vitamin_k' => $fact->vitamin_k ?? null,
                    'thiamin' => $fact->thiamin ?? null,
                    'riboflavin' => $fact->riboflavin ?? null,
                    'niacin' => $fact->niacin ?? null,
                    'vitamin_b6' => $fact->vitamin_b6 ?? null,
                    'folate' => $fact->folate ?? null,
                    'vitamin_b12' => $fact->vitamin_b12 ?? null,
                    'biotin' => $fact->biotin ?? null,
                    'pantothenic_acid' => $fact->pantothenic_acid ?? null,
                    'phosphorus' => $fact->phosphorus ?? null,
                    'iodine' => $fact->iodine ?? null,
                    'magnesium' => $fact->magnesium ?? null,
                    'zinc' => $fact->zinc ?? null,
                    'selenium' => $fact->selenium ?? null,
                    'copper' => $fact->copper ?? null,
                    'manganese' => $fact->manganese ?? null,
                    'chromium' => $fact->chromium ?? null,
                    'molybdenum' => $fact->molybdenum ?? null,
                    'omega_3' => $fact->omega_3 ?? null,
                    'omega_6' => $fact->omega_6 ?? null,
                    'soluble_fiber' => $fact->soluble_fiber ?? null,
                    'insoluble_fiber' => $fact->insoluble_fiber ?? null,
                    'starch' => $fact->starch ?? null,
                    'chloride' => $fact->chloride ?? null,
                    'fluoride' => $fact->fluoride ?? null,
                    'choline' => $fact->choline ?? null,
                    'phytosterols' => $fact->phytosterols ?? null,
                    'caffeine' => $fact->caffeine ?? null,
                    'theobromine' => $fact->theobromine ?? null,
                    'vitamin_b5' => $fact->vitamin_b5 ?? null,
                    'vitamin_b7' => $fact->vitamin_b7 ?? null,
                    'chlorophyll' => $fact->chlorophyll ?? null,
                    'inositol' => $fact->inositol ?? null,
                    'paba' => $fact->paba ?? null,
                    'quercetin' => $fact->quercetin ?? null,
                    'rutin' => $fact->rutin ?? null,
                    'lycopene' => $fact->lycopene ?? null,
                    'lutein_zeaxanthin' => $fact->lutein_zeaxanthin ?? null,
                    'betaine' => $fact->betaine ?? null,
                ];

            }else{

                $this->nutrition = [
                    'species_id'=>$this->s_id,
                    'user_id'=>Auth::user()->id,
                    'plant_part'=>null,
                    'serving_size' => null,
                    'calories' => null,
                    'total_fat' => null,
                    'saturated_fat' => null,
                    'trans_fat' => null,
                    'monounsaturated_fat' => null,
                    'polyunsaturated_fat' => null,
                    'cholesterol' => null,
                    'sodium' => null,
                    'total_carbohydrate' => null,
                    'dietary_fiber' => null,
                    'sugars' => null,
                    'protein' => null,
                    'vitamin_d' => null,
                    'calcium' => null,
                    'iron' => null,
                    'potassium' => null,
                    'vitamin_a' => null,
                    'vitamin_c' => null,
                    'vitamin_e' => null,
                    'vitamin_k' => null,
                    'thiamin' => null,
                    'riboflavin' => null,
                    'niacin' => null,
                    'vitamin_b6' => null,
                    'folate' => null,
                    'vitamin_b12' => null,
                    'biotin' => null,
                    'pantothenic_acid' => null,
                    'phosphorus' => null,
                    'iodine' => null,
                    'magnesium' => null,
                    'zinc' => null,
                    'selenium' => null,
                    'copper' => null,
                    'manganese' => null,
                    'chromium' => null,
                    'molybdenum' => null,
                    'omega_3' => null,
                    'omega_6' => null,
                    'soluble_fiber' => null,
                    'insoluble_fiber' => null,
                    'starch' => null,
                    'chloride' => null,
                    'fluoride' => null,
                    'choline' => null,
                    'phytosterols' => null,
                    'caffeine' => null,
                    'theobromine' => null,
                    'vitamin_b5' => null,
                    'vitamin_b7' => null,
                    'chlorophyll' => null,
                    'inositol' => null,
                    'paba' => null,
                    'quercetin' => null,
                    'rutin' => null,
                    'lycopene' => null,
                    'lutein_zeaxanthin' => null,
                    'betaine' => null,
                ];

            }

    }

    public function mount($id){

        $this->s_id = $id;

        $this->species = Species::find($id);

        $this->speciesNutrition = SpeciesNutritionFact::where('species_id',$id)->first();

    }

    public function update()
    {
        // Validation logic here (You can add your validation code here)
        $this->validate([
            'nutrition.plant_part' => 'required',
            'nutrition.serving_size' => 'required',
            'nutrition.calories' => 'required|numeric',
            'nutrition.total_fat' => 'required|numeric',
            'nutrition.protein' => 'required|numeric',
            'nutrition.total_carbohydrate' => 'required|numeric',
            'nutrition.sodium' => 'required|numeric',
            'nutrition.cholesterol' => 'required|numeric',
        ]);

        // If nutrition_id exists, update the existing record
        $exist = SpeciesNutritionFact::updateOrCreate(
        [
        'plant_part'=>$this->nutrition['plant_part'],
        'species_id'=>$this->nutrition['species_id']
        ],
        $this->nutrition);



        $this->nutrition = [];

        // Show notification
        return $this->notification([
            'title' => 'Success!',
            'description' => 'It worked',
            'icon' => 'success',
        ]);

        // Clear the $this->nutrition array

        // Redirect or other post-update logic here (You can add your redirection logic here if needed)
    }

    public function render()
    {

        $this->speciesNutritions = SpeciesNutritionFact::where('species_id',$this->s_id)->get();

        return view('livewire.admin.species.nutrition.nutrition-input');
    }
}
