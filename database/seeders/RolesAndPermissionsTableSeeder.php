<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(['Stakeholder', 'Admin', 'User'])
            ->each(function ($roleName) {
                Role::create([
                    'name' => $roleName
                ]);
            });
    }
}
