<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Organization;
use Tests\TestCase;

class CreateCompanyTest extends TestCase
{
    public function test_stakeholder_has_to_select_organization()
    {
        $this->actingAs($this->stakeholder)->get('/companies/create')
            ->assertSee('Select Organization');

        $response = $this->actingAs($this->stakeholder)->post('/companies', [
            'organization_id' => 1,
            'name' => 'Company 1'
        ]);
        $response->assertRedirect('/companies');
        $this->assertDatabaseHas('companies', [
            'organization_id' => 1,
            'name' => 'Company 1'
        ]);
    }

    public function test_admin_can_not_select_organization()
    {
        $this->actingAs($this->admin)->get('/companies/create')
            ->assertDontSee('Select Organization');

        $this->actingAs($this->admin)->post('/companies', [
            'organization_id' => Organization::latest('id')->first()->id,
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
