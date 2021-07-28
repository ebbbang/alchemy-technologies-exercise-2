<?php

namespace Tests\Feature;

use App\Models\Organization;
use Tests\TestCase;

class OrganizationsTest extends TestCase
{
    public function test_organizations_index()
    {
        $response = $this->actingAs($this->stakeholder)->get('/organizations');

        $response->assertSee(
            Organization::first()->name
        );

        $response = $this->actingAs($this->admin)->get('/organizations');

        $response->assertForbidden();

        $response = $this->actingAs($this->user)->get('/organizations');

        $response->assertForbidden();
    }
}
