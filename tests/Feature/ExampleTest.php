<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testRequiresEmailAndLogin()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422);
    }

    public function testUserLoginsSuccessfully()
    {
        $payload = ['username' => 'avatrezaei', 'password' => '@00d$ife'];

        $this->json('POST', '/api/login', $payload)
            ->assertStatus(200);
    }

    public function test_example()
    {
        $response = $this->get('/api/languages');

        $response->assertStatus(200);
    }

    public function testsRegistersSuccessfully()
    {
        $payload = [
            'username' => 'Avat',
            'email' => 'test@laravel.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'country' => 'iraq',
            'mobile_code' => '098',
        ];

        $this->json('post', '/api/register', $payload)
            ->assertStatus(422);
    }

    public function testsRequiresPasswordEmailAndName()
    {
        $this->json('post', '/api/register')
            ->assertStatus(422);
    }

    public function testUserIsLoggedOutProperly()
    {
        $user = User::where(['username'=>'avatrezaei'])->first();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        $this->json('get', '/api/languages', [], $headers)->assertStatus(200);
        $this->json('get', '/api/user/logout', [], $headers)->assertStatus(200);

    }

    public function testUserWithNullToken()
    {
        $user = User::where(['username'=>'avatrezaei'])->first();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        $user->tokens()->delete();

        $this->json('get', '/api/ticket', [], $headers)->assertStatus(401);
    }

    public function testsArticlesAreCreatedCorrectly()
    {
        $user = User::where(['username'=>'avatrezaei'])->first();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
            'name' => 'avat',
            'email' => 'avatrezaei88@gmail.com',
            'subject' => 'hello',
            'message' => 'test hello',
        ];

        $this->json('POST', '/api/ticket/create', $payload, $headers)
            ->assertStatus(200);
    }
}
