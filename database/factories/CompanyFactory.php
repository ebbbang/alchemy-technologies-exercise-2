<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'organization_id' => function () {
                if (!$organization = Organization::inRandomOrder()->first()) {
                    $organization = Organization::factory()->create();
                }

                return $organization->id;
            },
            'name' => $this->faker->unique()->company(),
        ];
    }
}
