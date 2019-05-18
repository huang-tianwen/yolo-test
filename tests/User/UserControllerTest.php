<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserControllerTest extends TestCase
{
	use DatabaseMigrations;
	public function testShow()
	{
        $user = factory(\App\Models\User::class)->create();
        $this->actingAs($user)
        		->json('GET', 'api/users/'.$user->id)
        		->seeJson([
					'status' => 'ok',
					'data' => $user->toArray(),
				]);
	}

	public function testSearch()
	{
        $user = factory(\App\Models\User::class)->create();
        $users = factory(\App\Models\User::class, 5)->create();
        $this->actingAs($user)
        	 	->json('POST', 'api/users/search',[
        	 		'item_per_page' => 10,
        	 		'current_page' => 1,
        	 	])
        	 	->seeJson([
        	 		'total_items' => 6
        	 	])
        		->seeJsonStructure([
					'status',
					'total_items',
					'data' => [
						'*' => [
							'first_name', 'last_name', 'age', 'email',
						]
					],
				]);
	}
}