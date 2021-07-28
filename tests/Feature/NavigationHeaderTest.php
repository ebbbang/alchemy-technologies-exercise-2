<?php

namespace Tests\Feature;

use Tests\TestCase;

class NavigationHeaderTest extends TestCase
{
    public function test_organizations_link()
    {
        $response = $this->actingAs($this->stakeholder)->get('home');

        $response->assertSee('Organizations');

        $response = $this->actingAs($this->admin)->get('home');

        $response->assertDontSee('Organizations');

        $response = $this->actingAs($this->user)->get('home');

        $response->assertDontSee('Organizations');
    }

    public function test_users_link()
    {
        $response = $this->actingAs($this->stakeholder)->get('home');

        $response->assertSee('Users');

        $response = $this->actingAs($this->admin)->get('home');

        $response->assertSee('Users');

        $response = $this->actingAs($this->user)->get('home');

        $response->assertDontSee('Users');
    }

    public function test_companies_link()
    {
        $response = $this->actingAs($this->stakeholder)->get('home');

        $response->assertSee('Companies');

        $response = $this->actingAs($this->admin)->get('home');

        $response->assertSee('Companies');

        $response = $this->actingAs($this->user)->get('home');

        $response->assertSee('Companies');
    }
}
