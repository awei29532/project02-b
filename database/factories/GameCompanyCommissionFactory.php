<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\GameCompany;
use App\Models\GameCompanyCommission;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(GameCompanyCommission::class, function (Faker $faker) {
    return [
        'company_id' => $faker->unique()->numberBetween(1, 10),
        'ratio_win' => $faker->randomFloat(3, 0, 100),
        'ratio_lose' => $faker->randomFloat(3, 0, 100),
        'status' => strval(rand(0,1))
    ];
});
