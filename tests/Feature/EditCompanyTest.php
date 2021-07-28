<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Organization;
use Tests\TestCase;

class EditCompanyTest extends TestCase
{
    public function test_stakeholder_has_to_select_organization()
    {
        $company = Company::factory()->create();

        $this->actingAs($this->stakeholder)->get("/companies/$company->id/edit")
            ->assertSee('Select Organization');

        $response = $this->actingAs($this->stakeholder)->put("/companies/$company->id", [
            'organization_id' => 1,
            'name' => 'Company 1'
        ]);

        $response->assertRedirect('/companies');

        $this->assertDatabaseHas('companies', [
            'organization_id' => 1,
            'name' => 'Company 1'
        ]);
    }

    public function test_admin_only_update_companies_in_their_organization()
    {
        $otherOrganization = Organization::where('id', '!=', $this->admin->organization_id)->first();
        $company = Company::factory()->create([
            'organization_id' => $otherOrganization->id
        ]);

        $this->actingAs($this->admin)->get("/companies/$company->id/edit")
            ->assertDontSee('Select Organization');

        $this->actingAs($this->admin)->put("/companies/$company->id", [
            'organization_id' => $otherOrganization->id,
            'name' => 'Company 2'
        ])->assertRedirect('/companies');

        $this->assertDatabaseHas('companies', [
            'organization_id' => $company->organization_id,
            'name' => $company->name
        ]);
    }

    public function test_admin_can_not_select_organization()
    {
        $company = Company::factory()->create([
            'organization_id' => $this->admin->organization_id
        ]);
        $this->actingAs($this->admin)->put("/companies/$company->id", [
            'name' => 'Company 2'
        ])->assertRedirect('/companies');
        $this->assertDatabaseHas('companies', [
            'organization_id' => $this->admin->organization_id,
            'name' => 'Company 2'
        ]);
    }

    public function test_user_cannot_create_company()
    {
        $this->actingAs($this->user)
            ->post('/companies')
            ->assertForbidden();
    }

    public function test_validation()
    {
        $response = $this->actingAs($this->stakeholder)->post('/companies');
        $response->assertSessionHasErrors([
            'organization_id' => 'The organization id field is required.',
            'name' => 'The name field is required.'
        ]);

        $company = Company::factory()->create();
        $response = $this->actingAs($this->stakeholder)->post('/companies', [
            'organization_id' => $company->organization_id,
            'name' => $company->name
        ]);
        $response->assertSessionHasErrors([
            'name' => 'The name has already been taken.'
        ]);

        $response = $this->actingAs($this->admin)->post('/companies');
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.'
        ])->assertSessionDoesntHaveErrors([
            'organization_id' => 'The organization id field is required.',
        ]);
    }
}
