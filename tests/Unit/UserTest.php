<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    //use DatabaseTransactions;
    //use RefreshDatabase;
    use DatabaseMigrations;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateUser()
    {

        $new_user = factory(User::class)->create();
        $get_user=User::latest()->first();

        $this->assertEquals($new_user->toArray(),$get_user->toArray());
    }
}
