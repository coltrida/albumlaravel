<?php

use App\User;
use App\Models\Album;
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

$cats = [
    'abstract',
    'animals',
    'city',
];

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Album::class, function (Faker $faker) use($cats) {
    return [
        'album_name' => $faker->name,
        'description' => $faker->text(128),
        'user_id' => User::inRandomOrder()->first()->id,
        'album_thumb' => $faker->imageUrl(120, 120, $faker->randomElement($cats) )
    ];
});

$factory->define(App\Models\Photo::class, function (Faker $faker) use($cats) {
    return [
        'album_id' => 1,                               // valore di default, poi tanto lo modifichiamo nel SeedPhotosTable
        'name' => $faker->text(64),
        'description' => $faker->text(128),
        'img_path' => $faker->imageUrl(640, 480, $faker->randomElement($cats) )
    ];
});