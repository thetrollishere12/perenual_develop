<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Store;
use App\Models\ShippingDomestic;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $store = Store::inRandomOrder()->first();

        $shipping = ShippingDomestic::where('store_id',$store->id)->inRandomOrder()->first();

        return [
            'store_id' => $store->id,
            'sku' => $this->faker->uuid(),
            'category' => $this->faker->word()." > ".$this->faker->word(),
            'style' => $this->faker->word(),
            'name' =>$this->faker->text(),
            'default_image' => $this->faker->randomDigitNotNull().'.jpg',
            'image' => 'marketplace/example/p-example/',
            'currency' => $store->currency,
            'price' => $this->faker->randomFloat(2,1,1000),
            'shippingMethod' => $shipping->id,
            'tags' => $this->faker->words(5),
            'quantity' => $this->faker->randomNumber(3, false),
        ];
    }
}
