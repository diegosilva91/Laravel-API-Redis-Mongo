<?php

/** @var Factory $factory */

use App\Candidate;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;


$factory->define(Candidate::class, function (Faker $faker) {
    $user_id = User::select('id')->pluck('id');
    return [
        //
        'name'=>$faker->name,
        'source'=>$faker->company,
        'owner'=>$faker->randomElement($user_id)
    ];
});
