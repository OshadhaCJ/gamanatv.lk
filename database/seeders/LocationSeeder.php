<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Seeds Sri Lanka's 9 provinces and 25 districts. Cities are left to be
     * added from the admin Bus form as needed (per-district), since a full
     * city list isn't needed up front.
     */
    public function run(): void
    {
        $data = [
            'Western' => ['Colombo', 'Gampaha', 'Kalutara'],
            'Central' => ['Kandy', 'Matale', 'Nuwara Eliya'],
            'Southern' => ['Galle', 'Matara', 'Hambantota'],
            'Northern' => ['Jaffna', 'Kilinochchi', 'Mannar', 'Vavuniya', 'Mullaitivu'],
            'Eastern' => ['Trincomalee', 'Batticaloa', 'Ampara'],
            'North Western' => ['Kurunegala', 'Puttalam'],
            'North Central' => ['Anuradhapura', 'Polonnaruwa'],
            'Uva' => ['Badulla', 'Monaragala'],
            'Sabaragamuwa' => ['Ratnapura', 'Kegalle'],
        ];

        foreach ($data as $provinceName => $districts) {
            $province = Province::firstOrCreate(['name' => $provinceName]);

            foreach ($districts as $districtName) {
                District::firstOrCreate([
                    'province_id' => $province->id,
                    'name' => $districtName,
                ]);
            }
        }
    }
}
