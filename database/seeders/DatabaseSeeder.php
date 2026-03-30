<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            ProgramSeeder::class,
            LocationSeeder::class,
            EnsureDemoNgoAdminSeeder::class,
        ]);

        // Dev/staging only: change password before production. Login at /login → redirects to /admin/dashboard.
        $superAdmin = User::query()->firstOrCreate(
            ['email' => 'admin@favoredk.org'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        if (! $superAdmin->hasRole('super_admin')) {
            $superAdmin->assignRole('super_admin');
        }

        $donor = User::query()->firstOrCreate(
            ['email' => 'donor@favoredk.org'],
            [
                'name' => 'Test Donor',
                'password' => bcrypt('password'),
            ]
        );
        if (! $donor->hasRole('donor')) {
            $donor->assignRole('donor');
        }
    }
}
