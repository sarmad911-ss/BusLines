<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoginPage()
    {
        $response = $this->get(route('loginView'));

        $response->assertStatus(200);
    }

    public function testIndexPage()
    {
        $response = $this->get(route('listTripView'));

        $response->assertRedirect(route('loginView'));
    }

    public function testSuccessLoginAction()
    {

        $response = $this->post(route('loginAction'), [
            'email' => 'admin@bus-lines.de',
            'password' => 'admin',
        ]);
        $response->assertSessionHas('user');
        $response->assertOk();
    }

    public function testUnSuccessLoginAction()
    {

        $response = $this->post(route('loginAction'), [
            'email' => 'wrong@email',
            'password' => 'wrong',
        ]);
        $response->assertJsonStructure(['errors' => 'password'])
            ->assertSessionMissing('user');
    }

    public function testLogoutAction()
    {
        $response = $this->get(route('logout'));
        $response->assertSessionMissing('user')
            ->assertRedirect(route('loginView'));
    }
}
