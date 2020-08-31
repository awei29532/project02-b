<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\GameCompany;
use App\Models\GameCompanyMaintenance;
use Carbon\Carbon;
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

$factory->define(GameCompanyMaintenance::class, function (Faker $faker) {
    $maintainable = [
        GameCompany::class
    ];
    return [
        'status' => strval(rand(0,1)),
        'type' => ($type = rand(1, 2)),
        'start_at' => ($type == 1 ? '1 2 * * 3' : Carbon::now()->subMinutes(rand(1, 55))),
        'end_at' => ($type == 1 ? '1 2 * * 3' : Carbon::now()->subMinutes(rand(1, 55))),
        'remark' => $faker->word,
        'maintainable_id' => GameCompany::inRandomOrder()->first()->id,
        'maintainable_type' => $faker->randomElement($maintainable)
    ];
});
