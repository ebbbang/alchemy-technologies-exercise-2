<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_register_link_is_not_present()
    {
        $response = $this->get('/');

        $response->assertDontSee('Register');

        $response = $this->get('/login');

        $response->assertDontSee('Register');
    }

    public function test_register_route_is_not_accessible()
    {
        $response = $this->get('/register');

        $response->assertNotFound();

        $response =  $this->post('/register');

        $response->assertNotFound();
    }
}
