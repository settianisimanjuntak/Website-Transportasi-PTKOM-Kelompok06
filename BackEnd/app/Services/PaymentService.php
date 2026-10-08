<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    private const BANKS = ['BCA', 'BRI', 'MANDIRI', 'BNI'];

    public static function createForBooking(Booking $booking, string $method): Payment
    {
        $reference = match ($method) {
            'qris' => 'QRIS-'.strtoupper(bin2hex(random_bytes(5))),
            'va' => self::BANKS[array_rand(self::BANKS)].'-'.random_int(100000000000, 999999999999),
            default => 'EWALLET-'.strtoupper(bin2hex(random_bytes(5))),
        };

        return Payment::create([
            'booking_id' => $booking->id,
            'method' => $method,
            'amount' => $booking->total,
            'status' => 'waiting',
            'provider' => 'mock',
            'reference' => $reference,
            'bank' => $method === 'va' ? explode('-', $reference)[0] : null,
            'qr_payload' => $method === 'qris' ? 'QRIS://TIKETBUS/'.$booking->code.'/AMOUNT/'.$booking->total : null,
            'expires_at' => $booking->expires_at,
        ]);
    }

    public static function expireIfNeeded(Booking $booking): void
    {
        if (! $booking->isExpired()) {
            return;
        }

        DB::transaction(function () use ($booking) {
            $booking->passengers()->where('status', 'active')->update([
                'status' => 'released',
                'seat_no' => null,
            ]);

            $booking->update(['status' => 'expired']);
            $booking->payment?->update(['status' => 'expired']);
        });
    }

    public static function markPaid(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'paid', 'paid_at' => now()]);

            $booking->payment?->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            foreach ($booking->passengers()->where('status', 'active')->get() as $passenger) {
                if ($passenger->ticket) {
                    continue;
                }

                $passenger->ticket()->create([
                    'booking_id' => $booking->id,
                    'code' => Ticket::generateCode(),
                    'qr_payload' => 'TKTC://TIKETBUS/'.$booking->code.'/'.$passenger->seat_no,
                    'status' => 'issued',
                    'issued_at' => now(),
                ]);
            }
        });
    }

    public static function expiryMinutes(): int
    {
        return max(1, Setting::int('booking_expiry_minutes', 15));
    }
}
