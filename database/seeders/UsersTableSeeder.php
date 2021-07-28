<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stakeholder = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        $stakeholder->assignRole('Stakeholder');

        Organization::eachById(function (Organization $organization) {
            $users = User::factory(mt_rand(3, 7))->create([
                'organization_id' => $organization->id
            ]);

            $users->each(function (User $user) {
                $randomProbability = mt_rand(0, 2);

                if ($randomProbability === 0) {
                    $user->assignRole('Admin');
                }

                if ($randomProbability === 1) {
                    $user->assignRole('User');
                }

                if ($randomProbability === 2) {
                    $user->assignRole('Admin');
                    $user->assignRole('User');
                }
            });
        });
    }
}
