<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
		$data = [
			'name' => $this->faker->firstName,
			'last_name' => $this->faker->lastName                                  ,
			'alias' => $this->faker->numerify('user-####'),
			'gender' => $this->faker->numberBetween(0, 1),
			'born' => time(),
			'passport' => $this->faker->numerify('##########'),
			'country' => $this->faker->word(),
			'city' => $this->faker->word(),
			'about' => $this->faker->paragraph(),
			'phone' => $this->faker->numerify('###########'),
			'site' => $this->faker->word(),
			'real_photo' => $this->faker->word().'jpg',
			'login' => $this->faker->unique()->safeEmail,
			'email' => $this->faker->unique()->safeEmail,
			'email_verified_at' => now(),
			'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
			'remember_token' => Str::random(10),
			'private_set' => '{}'
		];

		return $data;
	}
}
