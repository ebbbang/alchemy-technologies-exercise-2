<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UsersTest extends TestCase
{
    public function test_users_index()
    {
        $response = $this->actingAs($this->stakeholder)->get('/users');

        $response->assertSee(User::exceptStakeholders()->first()->name);

        $response = $this->actingAs($this->admin)->get('/users');

        $response->assertSee(
            User::where('organization_id', $this->admin->organization_id)->first()->name
        )->assertDontSee(
            User::where('organization_id', '!=', $this->admin->organization_id)->first()->name
        )->assertDontSee('Login');

        $response = $this->actingAs($this->user)->get('/users');

        $response->assertForbidden();
    }
}
