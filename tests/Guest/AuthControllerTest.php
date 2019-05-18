<?php
namespace Tests\Guest;

use TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
	use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');
    }
    public function testRegister()
    {
        $this->json('POST', 'api/register', 
        	[
        		'first_name' => 'Andi',
        		'last_name' => 'Huang',
        		'age' => '26',
        		'email' => 'tianwen.huang@hotmail.com',
        		'password' => '123456',
        		'password_confirmation' => '123456',
        	])
             ->seeJson([
                'status' => 'ok',
        		'first_name' => 'Andi',
        		'last_name' => 'Huang',
        		'age' => '26',
        		'email' => 'tianwen.huang@hotmail.com',
             ]);
    }
    public function testLogin()
    {
    	$user = factory(\App\Models\User::class)->create();
        $client = DB::table('oauth_clients')->find(2);
        $this->json('POST', 'api/login', 
        	[
        		'email' => $user->email,
        		'password' => '123456',
        		'client_id' => $client->id,
        		'client_secret' => $client->secret,
        	])
            ->seeJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token',
            ]);
    }
}