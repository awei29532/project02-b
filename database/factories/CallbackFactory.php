<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Callback;
use Faker\Generator as Faker;

$factory->define(Callback::class, function (Faker $faker) {
    return [
        'site_id' => 1,
        'member_id' => rand(1, 10),
        'phone' => $faker->phoneNumber,
        'ip' => $faker->ipv4,
        'status' => '0',
    ];
});
