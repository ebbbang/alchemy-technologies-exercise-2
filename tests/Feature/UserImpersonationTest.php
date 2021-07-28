<?php

namespace Tests\Feature;

use App\Models\User;
use Auth;
use Tests\TestCase;

class UserImpersonationTest extends TestCase
{
    public function test_only_stakeholder_can_impersonate_other_users()
    {
        $user = User::role(['Admin', 'User'])->first();

        $this->actingAs($this->stakeholder)
            ->get("users/$user->id/impersonate");

        $this->assertAuthenticatedAs($user);

        $response = $this->actingAs($this->admin)
            ->get("users/$user->id/impersonate");

        $response->assertForbidden();

        $response = $this->actingAs($this->user)
            ->get("users/$user->id/impersonate");

        $response->assertForbidden();
    }

    public function test_logout_sends_to_previous_user_if_impersonating()
    {
        $this->actingAs($this->stakeholder);

        $user = User::role(['Admin', 'User'])->first();

        Auth::user()->impersonate($user);

        $this->assertAuthenticatedAs($user);

        $this->post('logout');

        $this->assertAuthenticatedAs($this->stakeholder);
    }
}
