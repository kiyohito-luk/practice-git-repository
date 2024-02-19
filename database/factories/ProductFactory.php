<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [ 
            'company_id' => Company::factory(),
            'product_name' => $this->faker->word,
            'price' => $this->faker->numberBetween(100,1000),
            'stock' => $this->faker->numberBetween(1,99),
            'comment' => $this->faker->sentence,
            'img_path' => 'http://picsum.photos/200/300',
        ];
    }
}
