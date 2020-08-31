<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Announcement;
use Faker\Generator as Faker;

$factory->define(Announcement::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'content' => $faker->text,
        'lang' => 'zh-tw',
    ];
});
