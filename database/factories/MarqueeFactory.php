<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Marquee;
use Faker\Generator as Faker;

$factory->define(Marquee::class, function (Faker $faker) {
    return [
        'site_id' => 1,
        'lang' => 'zh-tw',
        'status' => '1',
        'content' => $faker->text,
    ];
});
