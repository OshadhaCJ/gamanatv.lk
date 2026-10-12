<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds empty placeholders for every credential the app reads from the
     * `settings` table, so they can be filled in later (e.g. via an admin
     * settings screen) without touching code or .env.
     */
    public function run(): void
    {
        $defaults = [
            // Google OAuth (Socialite)
            ['group' => 'google', 'key' => 'google_client_id', 'value' => null, 'is_secret' => false],
            ['group' => 'google', 'key' => 'google_client_secret', 'value' => null, 'is_secret' => true],
            ['group' => 'google', 'key' => 'google_redirect_uri', 'value' => url('/auth/google/callback'), 'is_secret' => false],

            // Paniwida.lk SMS / WhatsApp gateway
            ['group' => 'paniwida', 'key' => 'paniwida_api_url', 'value' => 'https://paniwida.lk/api/send', 'is_secret' => false],
            ['group' => 'paniwida', 'key' => 'paniwida_api_key', 'value' => null, 'is_secret' => true],
            ['group' => 'paniwida', 'key' => 'paniwida_sender_id', 'value' => 'GAMANA', 'is_secret' => false],

            // OTP delivery preference: "sms", "whatsapp", or "whatsapp_then_sms"
            // (whatsapp_then_sms = try WhatsApp first, fall back to SMS if it fails)
            ['group' => 'otp', 'key' => 'otp_preferred_method', 'value' => 'whatsapp_then_sms', 'is_secret' => false],
        ];

        foreach ($defaults as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                ['group' => $setting['group'], 'value' => $setting['value'], 'is_secret' => $setting['is_secret']]
            );
        }
    }
}
