<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin TiketBus',
            'email' => 'admin@tiketbus.test',
            'password' => 'password',
            'role' => 'admin',
            'phone' => '+62 811 0000 0001',
            'email_verified_at' => now(),
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@email.com',
            'password' => 'password',
            'role' => 'user',
            'phone' => '+62 812 3456 7890',
            'nik' => '3201011408920001',
            'birth_date' => '1992-08-14',
            'gender' => 'male',
            'points' => 1250,
            'email_verified_at' => now(),
        ]);

        $this->demoBookings($budi);
    }

    private function demoBookings(User $user): void
    {
        $upcoming = Schedule::whereDate('service_date', '>=', today())
            ->whereHas('bus', fn ($query) => $query->where('code', 'BUS-53-166'))
            ->orderBy('service_date')
            ->first();

        if ($upcoming) {
            $this->createPaidBooking($user, $upcoming, '02A', 'qris');
        }

        Schedule::whereDate('service_date', '<', today())
            ->where('status', '!=', 'cancelled')
            ->orderByDesc('service_date')
            ->take(2)
            ->get()
            ->each(function (Schedule $schedule, int $index) use ($user) {
                $occupied = $schedule->passengers()->pluck('seat_no')->all();
                $free = $schedule->bus->seats()->whereNotIn('seat_no', $occupied)->pluck('seat_no');
                if ($free->isNotEmpty()) {
                    $this->createPaidBooking($user, $schedule, $free->values()[$index % $free->count()], 'va');
                }
            });
    }

    private function createPaidBooking(User $user, Schedule $schedule, string $seatNo, string $method): void
    {
        $insuranceFee = Setting::int('insurance_fee', 5000);
        $subtotal = $schedule->price;
        $total = $subtotal + $insuranceFee;

        $booking = Booking::create([
            'code' => Booking::generateCode(),
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'contact_name' => $user->name,
            'contact_phone' => $user->phone,
            'contact_email' => $user->email,
            'is_self_passenger' => true,
            'protection' => false,
            'protection_fee' => 0,
            'insurance_fee' => $insuranceFee,
            'service_fee' => Setting::int('service_fee'),
            'subtotal' => $subtotal,
            'total' => $total,
            'payment_method' => $method,
            'status' => 'paid',
            'expires_at' => now()->addMinutes(15),
            'paid_at' => now()->subDay()->subMinutes(mt_rand(30, 600)),
        ]);

        $passenger = $booking->passengers()->create([
            'schedule_id' => $schedule->id,
            'seat_no' => $seatNo,
            'full_name' => $user->name,
            'nik' => $user->nik ?? '3201011408920001',
            'status' => 'active',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'method' => $method,
            'amount' => $total,
            'status' => 'paid',
            'provider' => 'mock',
            'reference' => strtoupper($method.'-'.random_int(10000000, 99999999)),
            'bank' => $method === 'va' ? 'BCA' : null,
            'qr_payload' => $method === 'qris' ? 'QRISMOCK-'.$booking->code : null,
            'expires_at' => now()->addMinutes(15),
            'paid_at' => $booking->paid_at,
        ]);

        $booking->tickets()->create([
            'booking_passenger_id' => $passenger->id,
            'code' => \App\Models\Ticket::generateCode(),
            'qr_payload' => 'TKTC:'.$booking->code.':'.$seatNo,
            'status' => 'issued',
            'issued_at' => $booking->paid_at,
        ]);
    }
}
