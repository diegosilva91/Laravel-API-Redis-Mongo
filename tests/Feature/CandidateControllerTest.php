<?php

namespace Tests\Feature;

use App\Candidate;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use phpDocumentor\Reflection\Types\String_;
use Tests\TestCase;
use JWTAuth;


class CandidateControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:refresh');
        $this->seed();
        $this->user=User::where(["id"=>1])->first();
        $this->token=auth()->login($this->user);
        //$this->token=JWTAuth::fromUser($user);
        $this->owner=User::where(["id"=>2])->first();

    }

    /**
     *  Create a candidate
     *
     *  @return void
     */
    public function testCreateSuccess()
    {
        $baseUrl = Config::get('app.url') . '/api/auth/lead';
        $candidate=['name'=>'Mi candidato','source'=>'Fotocasa','owner'=>$this->owner];
        $response=$this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
        ])->json('POST', $baseUrl . '/', $candidate);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'meta', 'data',
            ]);
    }
    /**
     * General access token success
     *
     * @return void
     */
    public function testCreateFailedTokenInvalid()
    {
        auth()->logout();
        $token=auth()->setTTL(1)->login($this->user);
        $baseUrl = Config::get('app.url') . '/api/auth/lead';
        $candidate=['name'=>'Mi candidato','source'=>'Fotocasa','owner'=>$this->owner];
        $response=$this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', $baseUrl . '/', $candidate);
        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'meta'=>['success','errors']
            ]);
    }

    public function testGetLeads()
    {
        $baseUrl = Config::get('app.url') . '/api/auth/leads';
        $response=$this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
        ])->json('GET', $baseUrl . '/');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'meta', 'data',
            ]);
    }

    public function testGetLead()
    {
        $candidate=factory(Candidate::class)->create()->first();
        $baseUrl = Config::get('app.url') . '/api/auth/lead/'.$candidate->id;
        $response=$this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
        ])->json('GET', $baseUrl . '/');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'meta', 'data',
            ]);
    }
}
