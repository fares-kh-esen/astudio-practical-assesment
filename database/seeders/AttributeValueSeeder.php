<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $attributes = Attribute::all();

        foreach ($projects as $project) {
            foreach ($attributes as $attribute) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'entity_id' => $project->id,
                    'entity_type' => Project::class,
                    'value' => match ($attribute->name) {
                        'department' => fake()->randomElement(['HR', 'IT', 'Finance']),
                        'start_date' => fake()->date(),
                        'end_date' => fake()->date(),
                        'budget' => fake()->randomNumber(5),
                        default => 'Unknown'
                    },
                ]);
            }
        }
    }
}
