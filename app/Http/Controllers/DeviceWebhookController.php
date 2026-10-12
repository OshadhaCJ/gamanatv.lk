<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusAttendance;
use App\Models\DevicePing;
use App\Models\ProcessingUnit;
use App\Models\Setting;
use App\Models\StorageSwapRequest;
use App\Models\StorageUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Receives status pings from the in-bus hardware:
 *
 *   on    — bus started, TV not playing yet
 *   play  — bus started and the TV is playing
 *   pause — bus started but playback is paused
 *   off   — bus stopped
 *
 * "time" is NOT a clock timestamp — confirmed against the legacy
 * bus_data table's real rows, it's an elapsed-seconds counter sent on
 * "off" and "pause": the number of seconds just played before that
 * session ended/paused. `recorded_at` is this server's own clock at
 * receipt time.
 *
 * "password" must match the `webhook_password` setting (editable on the
 * Settings page) — pings with a missing/wrong password are rejected
 * ("Fail") and never logged.
 *
 * Kept deliberately permissive otherwise — a ping from an unrecognised
 * device is still stored (with null foreign keys, flagged for review)
 * rather than rejected, so nothing is lost while a new device is being
 * registered.
 */
class DeviceWebhookController extends Controller
{
    public function store(Request $request): Response
    {
        $deviceId = $request->input('deviceID');
        $status = $request->input('sts');
        $time = $request->input('time');
        $penId = $request->input('penID');
        $password = $request->input('password');

        if (! filled($deviceId) || ! filled($status) || $time === null || ! filled($penId)) {
            return response('Invalid parameters');
        }

        $expectedPassword = Setting::get('webhook_password');
        if (! $expectedPassword || ! hash_equals((string) $expectedPassword, (string) $password)) {
            return response('Fail');
        }

        $processingUnit = ProcessingUnit::where('device_id', $deviceId)->first();
        $storageUnit = StorageUnit::where('pen_id', $penId)->first();
        $busId = $processingUnit?->bus?->id;
        $status = strtolower(trim($status));

        $this->trackStorageUnitPairing($storageUnit, $processingUnit);

        if ($busId) {
            $this->autoMarkAttendance($busId);
            $this->checkPendingSwap($busId, $penId);
        }

        [$flagged, $reason] = $this->detectAnomaly($busId, $processingUnit, $status);

        DevicePing::create([
            'bus_id' => $busId,
            'processing_unit_id' => $processingUnit?->id,
            'storage_unit_id' => $storageUnit?->id,
            'device_id' => $deviceId,
            'pen_id' => $penId,
            'status' => $status,
            'duration_seconds' => (int) $time,
            'is_flagged' => $flagged,
            'flag_reason' => $reason,
            'recorded_at' => now(),
        ]);

        return response('OK');
    }

    /**
     * Storage units move between processing units (SD cards get swapped
     * between buses). Whenever a storage unit shows up paired with a
     * different processing unit than last time, record the swap.
     */
    private function trackStorageUnitPairing(?StorageUnit $storageUnit, ?ProcessingUnit $processingUnit): void
    {
        if (! $storageUnit || ! $processingUnit) {
            return;
        }

        if ($storageUnit->last_processing_unit_id === $processingUnit->id) {
            return;
        }

        $storageUnit->update([
            'last_processing_unit_id' => $processingUnit->id,
            'last_paired_at' => now(),
        ]);
    }

    /**
     * A ping from the bus is proof it's running today — fill in today's
     * attendance as "running" if a coordinator hasn't already marked it
     * (manually, or by an earlier auto-mark).
     */
    private function autoMarkAttendance(int $busId): void
    {
        BusAttendance::firstOrCreate(
            ['bus_id' => $busId, 'date' => now()->toDateString()],
            ['status' => BusAttendance::RUNNING, 'auto_marked' => true]
        );
    }

    /**
     * If a coordinator logged a pending storage-device swap for this bus,
     * confirm it once a ping reports the new pen ID, or flag it as a
     * fault if a ping reports some other, unexpected pen ID.
     */
    private function checkPendingSwap(int $busId, string $penId): void
    {
        $pending = StorageSwapRequest::where('bus_id', $busId)
            ->where('status', StorageSwapRequest::PENDING)
            ->with('newStorageUnit', 'oldStorageUnit')
            ->first();

        if (! $pending) {
            return;
        }

        if ($pending->newStorageUnit->pen_id === $penId) {
            $pending->update(['status' => StorageSwapRequest::CONFIRMED, 'confirmed_at' => now()]);
            Bus::where('id', $busId)->update(['storage_unit_id' => $pending->new_storage_unit_id]);

            return;
        }

        if ($pending->oldStorageUnit && $pending->oldStorageUnit->pen_id === $penId) {
            // Still reporting the old device — swap just hasn't happened yet.
            return;
        }

        $pending->update([
            'status' => StorageSwapRequest::FAILED,
            'fault_reason' => "Expected pen ID {$pending->newStorageUnit->pen_id}, but the bus reported {$penId}.",
        ]);
    }

    /**
     * Pings can arrive out of order, or two devices' logs can get mixed
     * up. Flag anything that doesn't make sense given the last known
     * status for this bus, so it surfaces on the Logs page for review.
     *
     * @return array{0: bool, 1: ?string}
     */
    private function detectAnomaly(?int $busId, ?ProcessingUnit $processingUnit, string $status): array
    {
        if (! $processingUnit) {
            return [true, 'Unrecognised device ID — not registered to any processing unit.'];
        }

        if (! $busId) {
            return [true, 'Processing unit is not assigned to any bus.'];
        }

        $previous = DevicePing::where('bus_id', $busId)->latest('recorded_at')->first();

        if (! $previous) {
            return [false, null];
        }

        if ($previous->status === $status) {
            return [true, "Repeated \"{$status}\" status received twice in a row — logs may be out of order."];
        }

        if (in_array($status, [DevicePing::STATUS_OFF, DevicePing::STATUS_PAUSE], true)
            && $previous->status === DevicePing::STATUS_OFF) {
            return [true, "\"{$status}\" received with no active session running — logs may be swapped."];
        }

        return [false, null];
    }
}
