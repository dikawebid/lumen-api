<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class AuthTest extends TestCase
{
    public function testRegister()
    {
        $faker = Faker::create();
        $response = $this->call('POST', '/api/v1/register', [
            "username" => $faker->username,
            "name" => $faker->name,
            "email" => $faker->unique()->safeEmail,
            "password" => Hash::make($faker->password),
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testLogin()
    {
        $faker = Faker::create();
        $pass = $faker->password;
        $user = User::factory(\App\Models\User::class)->create(['password'=>Hash::make($pass)]);

        $response = $this->call('POST', '/v1/oauth/token', [
            "grant_type" => "password",
            "client_id" => "2",
            "client_secret" => "P1vyLEdOp43P9IhssnhWEYrqRCLa7FwTTxf3to8O",
            "username" => $user->username,
            "password" => $pass
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testProfile()
    {
        $user = User::factory(\App\Models\User::class)->create();
        $this->actingAs($user);

        $response = $this->call('GET', '/api/v1/me');
        $this->assertEquals(200, $response->status());
    }
}
