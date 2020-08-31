<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\ComplaintLetter;
use Faker\Generator as Faker;

$factory->define(ComplaintLetter::class, function (Faker $faker) {
    return [
        'site_id' => 1,
        'member_id' => rand(1, 10),
        'title' => $faker->title,
        'content' => $faker->text,
    ];
});
