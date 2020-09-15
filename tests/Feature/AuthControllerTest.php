<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use phpDocumentor\Reflection\Types\String_;
use Tests\TestCase;


class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        //Artisan::call('migrate:refresh');
        //$this->seed();
    }
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testBasicTest()
    {
        $response = $this->get('/');
        $user=['username'=>'tester','password'=>'PASSWORD'];
        $username = $user['username'];
        $password = $user['password'];

        $response->assertStatus(200);
        $response->assertOk();
    }

    /**
     *  Register users
     *
     *
     */
    public function testRegister()
    {
        $baseUrl = Config::get('app.url') . '/api/auth/register';
        $user=['username'=>'tester','password'=>'PASSWORD'];
        $username = $user['username'];
        $password = $user['password'];

        $response = $this->json('POST', $baseUrl . '/', [
            'username' => $username,
            'password' => $password
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'user', 'created',
            ]);
    }
    /**
     * General access token success
     *
     * @return void
     */
    public function testLoginSuccess()
    {
        $baseUrl = Config::get('app.url') . '/api/auth/login';
        $user=User::updateOrCreate([ "username"=>"tester",  "password"=>Hash::make("PASSWORD"),  "last_login"=> "2020-09-01 16:16:16",  "is_active"=> true,  "role"=> "manager" ]);
        $username = $user->username;
        $password = "PASSWORD";

        $response = $this->json('POST', $baseUrl . '/', [
            'username' => $username,
            'password' => $password
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'meta'=>['success','errors'],
                'data'=>['token','minutes_to_expire']
            ]);
        $token_receive=($response->getData())->data->token;
        //Cache::shouldReceive('connection')->once()->with('default')->andReturnTrue();

        Cache::shouldReceive('store->redis')->shouldReceive('put')
            ->with('token', $token_receive)
            ->andReturn(true);
        //$cache=Cache::shouldReceive('store->redis')->shouldReceive('put')->once()->with($token_receive)->andReturn($token_receive);
        //$cache->andReturnTrue();
        /*$redis = Cache::getRedis();
        $redis->shouldReceive('connection')->once()->with('default')->andReturn(Cache::getRedis());
        $redis->getRedis()->shouldReceive('get')->once()->with('prefix:foo')->andReturn(1);
        $this->assertEquals(1, $redis->get('foo'));*/
    }

    /**
     * General access token failed
     *
     * @return void
     */
    public function testLoginFailed()
    {
        $baseUrl = Config::get('app.url') . '/api/auth/login';
        $user=User::updateOrCreate([ "username"=>"tester",  "password"=>Hash::make("PASSWORD"),  "last_login"=> "2020-09-01 16:16:16",  "is_active"=> true,  "role"=> "manager" ]);
        $username = $user->username;
        $invalid_password = "PASS";
        $response = $this->json('POST', $baseUrl . '/', [
            'username' => $username,
            'password' => $invalid_password
        ]);

        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'meta'=>['success','errors']
            ]);
    }
    /**
     * General access with form
     *
     * @return void
     */
    public function testLoginForm()
    {
        $baseUrl = Config::get('app.url') . '/login';
        $user = User::updateOrCreate(["username" => "tester", "password" => Hash::make("PASSWORD"), "last_login" => "2020-09-01 16:16:16", "is_active" => true, "role" => "manager"]);
        $username = $user->username;
        $password = "PASSWORD";
        $response = $this->json('POST', $baseUrl . '/', [
            'username' => $username,
            'password' => $password
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'user', 'login',
            ]);
    }
    /**
     * General register with form
     *
     * return @void
     */
    public function testRegisterForm()
    {
        $baseUrl = Config::get('app.url') . '/register';
        $user=['username'=>'tester','password'=>'PASSWORD'];
        $username = $user['username'];
        $password = $user['password'];
        //$token=Auth::attempt([$username,$password]);
        $response = $this->json('POST', $baseUrl . '/', [
            'username' => $username,
            'password' => $password
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'user', 'created',
            ]);
    }

}
