<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * User Factory class.
 * 
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'fullname' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'document' => $this->faker->numerify('###########'),
            'type' => 'customer',
            'balance' => 50000
        ];
    }
}
