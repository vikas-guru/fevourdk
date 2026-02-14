<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\District;
use App\Models\City;

class LocationSeeder extends Seeder
{
    public function run()
    {
        // Karnataka States and Districts
        $karnataka = State::firstOrCreate([
            'code' => 'KA'
        ], [
            'name' => 'Karnataka',
            'is_active' => true
        ]);

        // Karnataka Districts
        $districts = [
            'Bangalore Urban', 'Bangalore Rural', 'Mysore', 'Hubli', 'Mangalore',
            'Belgaum', 'Gulbarga', 'Bidar', 'Raichur', 'Koppal',
            'Bellary', 'Chitradurga', 'Davanagere', 'Chikmagalur', 'Shimoga',
            'Udupi', 'Dakshina Kannada', 'Uttara Kannada', 'Haveri', 'Gadag',
            'Kolar', 'Chikkaballapur', 'Ramanagara', 'Mandya', 'Hassan',
            'Kodagu', 'Tumkur', 'Chamarajanagar', 'Yadgir', 'Vijayanagara'
        ];

        foreach ($districts as $districtName) {
            $district = District::firstOrCreate([
                'name' => $districtName,
                'state_id' => $karnataka->id
            ], [
                'is_active' => true
            ]);

            // Add some major cities for each district
            $this->createCitiesForDistrict($district);
        }
    }

    private function createCitiesForDistrict($district)
    {
        $cities = [
            'Bangalore Urban' => ['Bangalore', 'Whitefield', 'Electronic City', 'Indiranagar', 'Jayanagar'],
            'Bangalore Rural' => ['Ramanagara', 'Channapatna', 'Kanakapura', 'Magadi', 'Nelamangala'],
            'Mysore' => ['Mysore', 'Nanjangud', 'Srirangapatna', 'Hunsur', 'K.R. Pet'],
            'Hubli' => ['Hubli', 'Dharwad', 'Navalgund', 'Kundgol', 'Kalghatgi'],
            'Mangalore' => ['Mangalore', 'Ullal', 'Bantwal', 'Puttur', 'Moodbidri'],
            'Belgaum' => ['Belgaum', 'Khanapur', 'Gokak', 'Athani', 'Raybag'],
            'Gulbarga' => ['Gulbarga', 'Chincholi', 'Sedam', 'Shahapur', 'Wadi'],
            'Bidar' => ['Bidar', 'Humnabad', 'Bhalki', 'Aurad', 'Basavakalyan'],
            'Raichur' => ['Raichur', 'Manvi', 'Sindhanur', 'Yelburga', 'Lingsugur'],
            'Koppal' => ['Koppal', 'Gangavati', 'Kushtagi', 'Yelbarga', 'Kanakagiri'],
            'Bellary' => ['Bellary', 'Hospet', 'Kudligi', 'Siruguppa', 'Hagari Bommanahalli'],
            'Chitradurga' => ['Chitradurga', 'Hiriyur', 'Challakere', 'Molakalmuru', 'Holalkere'],
            'Davanagere' => ['Davanagere', 'Harihara', 'Honnavalli', 'Nyamathi', 'Channagiri'],
            'Chikmagalur' => ['Chikmagalur', 'Tarikere', 'Kadur', 'Sringeri', 'Narasimharajapura'],
            'Shimoga' => ['Shimoga', 'Bhadravati', 'Sagar', 'Shikaripura', 'Tirthahalli'],
            'Udupi' => ['Udupi', 'Kundapura', 'Brahmavara', 'Karkala', 'Moodbidri'],
            'Dakshina Kannada' => ['Mangalore', 'Puttur', 'Bantwal', 'Sulya', 'Vittla'],
            'Uttara Kannada' => ['Karwar', 'Sirsi', 'Yellapur', 'Dandeli', 'Haliyal'],
            'Haveri' => ['Haveri', 'Ranebennur', 'Byadgi', 'Shiggaon', 'Savanur'],
            'Gadag' => ['Gadag', 'Ron', 'Nargund', 'Mundargod', 'Lakshmeshwar'],
            'Kolar' => ['Kolar', 'Bangarpet', 'Malur', 'Mulbagal', 'Srinivaspur'],
            'Chikkaballapur' => ['Chikkaballapur', 'Bagepalli', 'Chintamani', 'Gauribidanur', 'Gudibanda'],
            'Ramanagara' => ['Ramanagara', 'Channapatna', 'Kanakapura', 'Magadi', 'Harohalli'],
            'Mandya' => ['Mandya', 'Maddur', 'Malavalli', 'Nagamangala', 'Pandavapura'],
            'Hassan' => ['Hassan', 'Arsikere', 'Belur', 'Channarayapatna', 'Sakleshpur'],
            'Kodagu' => ['Madikeri', 'Virajpet', 'Somwarpet', 'Kushalnagar', 'Ponnampet'],
            'Tumkur' => ['Tumkur', 'Tiptur', 'Sira', 'Pavagada', 'Koratagere'],
            'Chamarajanagar' => ['Chamarajanagar', 'Kollegal', 'Gundlupet', 'Yelandur', 'Hanur'],
            'Yadgir' => ['Yadgir', 'Shahapur', 'Wadi', 'Surpur', 'Hunsagi'],
            'Vijayanagara' => ['Hospet', 'Kampli', 'Kottur', 'Kudligi', 'Hagaribommanahalli']
        ];

        $districtCities = $cities[$district->name] ?? [$district->name];

        foreach ($districtCities as $cityName) {
            City::firstOrCreate([
                'name' => $cityName,
                'district_id' => $district->id
            ], [
                'is_active' => true
            ]);
        }
    }
}
