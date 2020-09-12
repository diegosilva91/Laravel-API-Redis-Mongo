<?php

/** @var Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

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

$factory->define(User::class, function (Faker $faker) {
    $created_at=$faker->dateTimeBetween('-5 years','now');
    $last_login=$faker->dateTimeBetween($created_at,'now');
    //$roleIds = \App\Role::select('id')->pluck('id');
    return [
        'username' => $faker->unique()->userName,
        'password' => $faker->password,
        'last_login' => $last_login,
        'is_active'=>$faker->boolean(60),
        //'role_id' => $faker->randomElement($roleIds),
        'created_at'=>$created_at
    ];
});

