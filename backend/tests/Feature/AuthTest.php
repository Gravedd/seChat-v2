<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class AuthTest
 *
 * test the auth system
 *      register/login/checklogin/logout
 * @package Tests\Feature
 *
 */
class AuthTest extends TestCase
{

    private $password = '12345678';
    /**
     * A basic feature test example.
     *
     * @return string
     */
    public function test_successRegister()
    {
        $email = Str::random('5').'@gmail.com';
        $response = $this->postJson('/api/register', [
            'name' => Str::random('4').'User',
            'email' => $email,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ]);
        $response->assertStatus(201);
        return $email;
    }

    /**
     * @depends test_successRegister
     */
    public function test_successLogin($email) {
        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => $this->password,
        ]);
        $response->assertStatus(201);
        $responsejson = $response->json();
        return $responsejson['token'];
    }

    /**
     * @depends test_successLogin
     */
    public function test_successCheckLogin($token) {
        $response = $this->withHeaders(['Authorization'=> 'Bearer '. $token, 'accept'=>'application/json'])->get('/api/checkauth');
        $response->assertOk();
        return $token;
    }

    /**
     * @depends test_successCheckLogin
     */
    public function test_logout($token) {
        $response = $this->withHeaders(['Authorization'=> 'Bearer '. $token, 'accept'=>'application/json'])->post('/api/logout');
        $response->assertStatus(201);
    }


    public function test_unsuccessRegister()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => '',
            'password' => $this->password,
            'password_confirmation' => $this->password.'1',
        ]);
        $response->assertStatus(422);
    }

    public function test_unsuccessLogin() {
        $response = $this->postJson('/api/login', [
            'email' => 'dsad',
            'password' => 'dsadas',
        ]);
        $response->assertStatus(422);
    }

    public function test_unsuccessCheckLogin() {
        $response = $this->withHeaders(['Authorization'=> 'Bearerdasd'.'dasdasdasd', 'accept'=>'application/json'])->get('/api/checkauth');
        $response->assertStatus(401);
    }

    public function test_unsuccessLogout() {
        $response = $this->withHeaders(['Authorization'=> 'Bearer '. 'dasdasdasd', 'accept'=>'application/json'])->post('/api/logout');
        $response->assertStatus(401);
    }
}
