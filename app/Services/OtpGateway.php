<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Sends OTP codes through Paniwida.lk, honouring the preferred delivery
 * method stored in the `settings` table:
 *
 *   - "sms"               → SMS only
 *   - "whatsapp"           → WhatsApp only
 *   - "whatsapp_then_sms"  → try WhatsApp first, fall back to SMS if it fails
 *
 * Credentials (API URL / key / sender id) are also read from `settings`
 * rather than .env, so they can be managed from an admin screen later.
 */
class OtpGateway
{
    public function send(string $phone, string $code): bool
    {
        $method = Setting::get('otp_preferred_method', 'whatsapp_then_sms');
        $message = "Your Gamana TV verification code is {$code}. It expires in 10 minutes.";

        return match ($method) {
            'sms' => $this->sendSms($phone, $message),
            'whatsapp' => $this->sendWhatsapp($phone, $message),
            'whatsapp_then_sms' => $this->sendWhatsapp($phone, $message) || $this->sendSms($phone, $message),
            default => $this->sendSms($phone, $message),
        };
    }

    private function sendWhatsapp(string $phone, string $message): bool
    {
        return $this->callPaniwida($phone, $message, 'whatsapp');
    }

    private function sendSms(string $phone, string $message): bool
    {
        return $this->callPaniwida($phone, $message, 'sms');
    }

    /**
     * Generic Paniwida.lk API call. Adjust the payload shape to match
     * Paniwida's actual API documentation once you have API access —
     * this is written defensively so a failure never breaks registration,
     * it just falls back / gets logged.
     */
    private function callPaniwida(string $phone, string $message, string $channel): bool
    {
        $apiUrl = Setting::get('paniwida_api_url');
        $apiKey = Setting::get('paniwida_api_key');
        $senderId = Setting::get('paniwida_sender_id', 'GAMANA');

        if (! $apiUrl || ! $apiKey) {
            Log::info("[OTP:{$channel}] (no gateway configured) code for {$phone}: {$message}");

            return false;
        }

        try {
            $response = Http::timeout(10)
                ->withToken($apiKey)
                ->post($apiUrl, [
                    'channel' => $channel,
                    'sender_id' => $senderId,
                    'to' => $phone,
                    'message' => $message,
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::warning("Paniwida {$channel} send failed for {$phone}: ".$response->body());

            return false;
        } catch (\Throwable $e) {
            Log::warning("Paniwida {$channel} send threw for {$phone}: ".$e->getMessage());

            return false;
        }
    }
}
