<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public bool $seed = true;

    protected User $stakeholder;
    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stakeholder = User::factory()->stakeholder()->create();
        $this->stakeholder->assignRole('Stakeholder');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('Admin');

        $this->user = User::factory()->create();
        $this->user->assignRole('User');
    }
}
