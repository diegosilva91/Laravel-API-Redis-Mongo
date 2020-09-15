<?php

namespace App\Http\Controllers;
use Faker\Factory as Faker;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function home(){
        $faker = Faker::create();
        $created_at=$faker->dateTimeBetween('-5 years','now');
        $last_login=$faker->dateTimeBetween($created_at,'now');
        $roleIds = \App\Role::select('id')->pluck('id');
        $user=\App\User::create([
            'username' => $faker->userName,
            'password' => $faker->password,
            'last_login' => $last_login,
            'is_active'=>$faker->boolean(60),
            'role_id' => $faker->randomElement($roleIds),
            'created_at'=>$created_at
        ]);
        dd($user);
    }
}
