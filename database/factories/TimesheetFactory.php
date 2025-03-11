<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Timesheet>
 */
class TimesheetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_name' => $this->faker->sentence(4),
            'date' => $this->faker->date,
            'hours' => $this->faker->randomFloat(1, 1, 8),
            'user_id' => \App\Models\User::factory(),
            'project_id' => \App\Models\Project::factory(),
        ];
    }
}
