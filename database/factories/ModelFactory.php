<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use JeroenNoten\LaravelNewsletter\Models\Newsletter;

$factory->define(Newsletter::class, function (Faker\Generator $faker) {
    return [
        'subject' => $faker->sentence,
        'body' => $faker->paragraph,
        'sent_at' => $faker->dateTime
    ];
});
