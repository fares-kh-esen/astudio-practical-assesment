<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttributeValue>
 */
class AttributeValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attribute_id' => Attribute::factory(),
            'entity_id' => Project::factory(),
            'entity_type' => Project::class,
            'value' => $this->faker->randomElement([
                'HR', 'IT', 'Finance', '2024-06-01', '2024-12-31', 50000
            ]),
        ];
    }
}
