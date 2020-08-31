<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Sms;
use Faker\Generator as Faker;

$factory->define(Sms::class, function (Faker $faker) {
    return [
        'site_id' => 1,
        'content' => $faker->text,
        'send_at' => $faker->dateTime,
    ];
});
