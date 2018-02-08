<?php

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

$factory->define(App\Models\User::class, function (Faker $faker) {

    $data_time = $faker->date . ' ' .$faker->time;

    static $password;


    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'is_admin' => false,
        'activated' => true,
        'password' => $password ?: $password = bcrypt('123456'),
        'remember_token' => str_random(10),
        'created_at' => $data_time,
        'updated_at' => $data_time,
    ];
});
