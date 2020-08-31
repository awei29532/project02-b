<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'site_id' => 1,
        'member_ids' => json_encode(['1', '2', '3', '10']),
        'title' => $faker->title,
        'content' => $faker->text,
        'send_at' => $faker->dateTime,
    ];
});
