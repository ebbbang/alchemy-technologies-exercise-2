<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Organization;
use Tests\TestCase;

class CompaniesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Organization::eachById(function (Organization $organization) {
            Company::factory()->create([
                'organization_id' => $organization->id
            ]);
        });
    }

    public function test_companies_index()
    {
        $response = $this->actingAs($this->stakeholder)->get('/companies');

        $response->assertSee(Company::first()->name)
            ->assertSee('Create')
            ->assertSee('Edit')
            ->assertSee('Delete');

        $response = $this->actingAs($this->admin)->get('/companies');

        $response->assertSee(
            Company::where('organization_id', $this->admin->organization_id)->first()->name
        )->assertDontSee(
            Company::where('organization_id', '!=', $this->admin->organization_id)->first()->name
        )->assertSee('Create')
            ->assertSee('Edit')
            ->assertSee('Delete');

        $response = $this->actingAs($this->user)->get('/companies');

        $response->assertSee(
            Company::where('organization_id', $this->user->organization_id)->first()->name
        )->assertDontSee(
            Company::where('organization_id', '!=', $this->user->organization_id)->first()->name
        )->assertDontSee('Create')
            ->assertDontSee('Edit')
            ->assertDontSee('Delete');
    }

    public function test_companies_create()
    {
        $response = $this->actingAs($this->stakeholder)->get('/companies/create');
        $response->assertSee('Select Organization')
            ->assertViewHas('organizations', Organization::all())
            ->assertSee(Organization::first()->name);

        $response = $this->actingAs($this->admin)->get('/companies/create');
        $response->assertDontSee('Select Organization')
            ->assertDontSee(Organization::first()->name);

        $this->actingAs($this->user)
            ->get('/companies/create')
            ->assertForbidden();
    }

    public function test_companies_delete()
    {
        $company = Company::first();

        $this->actingAs($this->stakeholder)->delete("/companies/$company->id");
        $this->assertDatabaseMissing('companies', $company->only('id'));

        $company = Company::factory()->create([
            'organization_id' => $this->admin->organization_id
        ]);

        $this->actingAs($this->admin)->delete("/companies/$company->id");
        $this->assertDatabaseMissing('companies', $company->only('id'));

        $company = Company::where('organization_id', '!=', $this->admin->organization_id)->first();

        $this->actingAs($this->admin)->delete("/companies/$company->id");
        $this->assertDatabaseHas('companies', $company->only('id'));

        $company = Company::first();

        $this->actingAs($this->user)->delete("/companies/$company->id")
            ->assertForbidden();
    }
}
