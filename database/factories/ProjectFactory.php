<?php

use Faker\Generator as Faker;

$factory->define(Birdboard\Project::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'owner_id' => function() {
        return factory(Birdboard\User::class)->create()->id;
    }
    ];
});
