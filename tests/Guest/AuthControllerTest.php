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
}