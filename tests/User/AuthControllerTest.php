<?php
namespace Tests\User;

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
	public function testMe()
	{
        $user = factory(\App\Models\User::class)->create();
        $this->actingAs($user)
        	 ->json('GET', 'api/me')
             ->seeJson([
                 'status' => 'ok',
             ])
             ->seeJsonStructure([
             	'status',
             	'data' => [
             		'first_name',
             		'last_name',
             		'age',
             		'email',
             	]
             ]);
	}
}