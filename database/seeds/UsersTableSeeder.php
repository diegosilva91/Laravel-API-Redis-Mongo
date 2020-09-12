<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        factory(App\User::class, 50)->create()->each(function ($user) {
            $user->assignRole('manager');
            $user->createdBy()->save(factory(App\Candidate::class)->make());
        });
    }
}
