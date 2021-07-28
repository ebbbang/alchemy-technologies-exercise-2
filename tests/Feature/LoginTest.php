<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_with_correct_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'password'
        ]);

        $response->assertLocation('/home');
    }

    public function test_login_with_wrong_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'aasdasd'
        ]);

        $response->assertSessionHasErrors([
            'email' => 'These credentials do not match our records.'
        ]);
    }

    public function test_validations()
    {
        $response = $this->post('/login');

        $response->assertSessionHasErrors([
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
        ]);
    }
}
