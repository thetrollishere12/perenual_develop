<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {   

        $product = Product::leftJoin('product_details','products.id','=','product_details.product_id')
        ->select('products.*','product_details.cycle','product_details.width','product_details.height','product_details.watering','product_details.sun_exposure','product_details.origin','product_details.color','product_details.pet_friendly','product_details.poisonous','product_details.suitable_location','product_details.maintenance','product_details.growth_rate','product_details.flowering_season','product_details.fruiting_season','product_details.fertilizer','product_details.humidity','product_details.soil','product_details.hardiness','product_details.pruning')
        ->where('product_details.product_id','=',null)
        ->inRandomOrder()
        ->first();

        return [
            'product_id' => $product->id,
            'cycle' => $this->faker->randomElement(['perennial','annual','biennial']),
            'width' => $this->faker->randomFloat(2),
            'height' => $this->faker->randomFloat(2),
            'watering' => $this->faker->randomElement(['none','minimum','average','frequent']),
            'sun_exposure' => $this->faker->randomElement(['part shade','full shade','full sun','sun-part shade']),
            'origin' => $this->faker->randomElement(['CA','US','UK','AU']),
            'color' => $this->faker->randomElements(['red','yellow','orange','white','black','blue','purple','violet','green'],4),
            'pet_friendly' => $this->faker->randomElement([null,'no','yes']),
            'poisonous' => $this->faker->randomElement([null,'no','yes']),
            'edible' => $this->faker->randomElement([null,'no','yes']),
            'suitable_location' => $this->faker->randomElements(['office','living room','garden','outside','balcony','bedroom','bathroom'],4),
            'maintenance' => $this->faker->randomElement(['none','low','moderate','high']),
            'growth_rate' => $this->faker->randomElement(['none','low','moderate','high']),
            'flowering_season' => $this->faker->randomElement(['summer','winter','autumn','spring']),
            'fruiting_season' => $this->faker->randomElement(['summer','winter','autumn','spring']),
            'fertilizer' => $this->faker->randomElement(['weekly','monthly','quarterly','annually']),
            'humidity' => $this->faker->randomElement([20,40,60,80]),
            'soil' => $this->faker->randomElements(['gravel','clay','sandy','silty','loamy','well-drained','loose dirt','moss'],3),
            'hardiness' => null,
            'pruning' => $this->faker->randomElement(['none','minimum','average','frequent']),
        ];
    }
}
