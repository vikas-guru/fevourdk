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
        ]);

        // Create super admin user
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@favoredk.org',
            'password' => bcrypt('password'),
        ]);
        $superAdmin->assignRole('super_admin');

        // Create test NGO admin
        $ngoAdmin = User::factory()->create([
            'name' => 'NGO Admin',
            'email' => 'ngo@favoredk.org',
            'password' => bcrypt('password'),
        ]);
        $ngoAdmin->assignRole('ngo_admin');

        // Create test donor
        $donor = User::factory()->create([
            'name' => 'Test Donor',
            'email' => 'donor@favoredk.org',
            'password' => bcrypt('password'),
        ]);
        $donor->assignRole('donor');
    }
}
