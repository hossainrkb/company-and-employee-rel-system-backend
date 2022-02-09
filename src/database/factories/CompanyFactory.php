<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
    	return [
            'name' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->email(),
            'password' => Hash::make("C123456"),
            'current_sub_start_from' => Carbon::now()->toDateTimeString(),
            'current_sub_when_end' => Carbon::now()->addDays(10)->toDateTimeString(),
            'sub_fee' => rand(500,1500),
    	];
    }
}
