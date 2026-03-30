<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\NGO;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Ensures the demo NGO exists and ngo@favoredk.org is linked to it.
 * Safe to run multiple times; fixes "NGO not found or access denied" after partial or old seeds.
 */
class EnsureDemoNgoAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (! City::query()->exists()) {
            $this->call(LocationSeeder::class);
        }

        $city = City::query()->with('district')->first();
        if (! $city) {
            throw new \RuntimeException('No cities in database; run LocationSeeder first.');
        }

        $district = $city->district;

        $demoNgo = NGO::query()->firstOrCreate(
            ['slug' => 'demo-ngo-seed'],
            [
                'name' => 'Demo NGO',
                'registration_number' => 'SEED-NGO-REG-001',
                'pan' => 'ABCDE1111F',
                'email' => 'demo.ngo@favoredk.org',
                'phone' => '9876543210',
                'address' => '1 Demo Street, Bengaluru',
                'city_id' => $city->id,
                'state_id' => $district?->state_id,
                'district_id' => $district?->id,
                'verification_status' => 'verified',
                'verified_at' => now(),
                'is_active' => true,
                'login_geo_policy' => 'none',
            ]
        );

        $ngoAdmin = User::query()->firstOrCreate(
            ['email' => 'ngo@favoredk.org'],
            [
                'name' => 'NGO Admin',
                'password' => bcrypt('password'),
                'ngo_id' => $demoNgo->id,
            ]
        );

        $ngoAdmin->forceFill(['ngo_id' => $demoNgo->id])->save();

        if (! $ngoAdmin->hasRole('ngo_admin')) {
            $ngoAdmin->assignRole('ngo_admin');
        }
    }
}
