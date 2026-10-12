<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class AlertSettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(
            ['key' => 'route_overrun_markup_percent'],
            ['group' => 'alerts', 'value' => '20', 'is_secret' => false]
        );

        Setting::firstOrCreate(
            ['key' => 'webhook_password'],
            ['group' => 'webhook', 'value' => 'Gamana1234', 'is_secret' => true]
        );
    }
}
