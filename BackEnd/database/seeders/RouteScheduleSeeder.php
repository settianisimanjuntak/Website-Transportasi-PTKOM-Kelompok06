<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Booking;
use App\Models\City;
use App\Models\Operator;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Terminal;
use Illuminate\Database\Seeder;

class RouteScheduleSeeder extends Seeder
{
    private array $routes = [];

    private array $services = [
        [
            'route' => ['Jakarta', 'Yogyakarta'], 'duration' => 495, 'bus' => 'BUS-42-118',
            'from' => 'Terminal Pulo Gebang', 'to' => 'Terminal Giwangan',
            'depart' => '16:30', 'arrive' => '00:45', 'offset' => 1, 'price' => 230000,
            'stops' => [['name' => 'Rest Area KM 207 Cipali', 'time' => '20:00', 'note' => 'Istirahat & makan malam (30 menit)']],
        ],
        [
            'route' => ['Jakarta', 'Yogyakarta'], 'duration' => 495, 'bus' => 'BUS-42-329',
            'from' => 'Terminal Pulo Gebang', 'to' => 'Terminal Giwangan',
            'depart' => '07:30', 'arrive' => '17:00', 'offset' => 0, 'price' => 230000,
        ],
        [
            'route' => ['Jakarta', 'Yogyakarta'], 'duration' => 465, 'bus' => 'BUS-42-101',
            'from' => 'Terminal Pulo Gebang', 'to' => 'Terminal Giwangan',
            'depart' => '18:00', 'arrive' => '01:45', 'offset' => 1, 'price' => 420000,
        ],
        [
            'route' => ['Jakarta', 'Yogyakarta'], 'duration' => 510, 'bus' => 'BUS-53-208',
            'from' => 'Terminal Pulo Gebang', 'to' => 'Terminal Giwangan',
            'depart' => '19:30', 'arrive' => '04:00', 'offset' => 1, 'price' => 195000,
        ],
        [
            'route' => ['Jakarta', 'Surabaya'], 'duration' => 690, 'bus' => 'BUS-42-329',
            'from' => 'Terminal Pulo Gebang', 'to' => 'Terminal Purabaya Bungurasih',
            'depart' => '18:00', 'arrive' => '05:30', 'offset' => 1, 'price' => 375000,
            'stops' => [
                ['name' => 'Rest Area KM 207 Cipali', 'time' => '21:30', 'note' => 'Istirahat & makan malam prasmanan gratis (30 menit)'],
                ['name' => 'Terminal Tirtonadi (Solo)', 'time' => '03:30', 'note' => 'Transit & penurunan penumpang wilayah Surakarta'],
            ],
        ],
        [
            'route' => ['Jakarta', 'Surabaya'], 'duration' => 650, 'bus' => 'BUS-280-84',
            'from' => 'Terminal Pulo Gebang', 'to' => 'Terminal Purabaya Bungurasih',
            'depart' => '20:00', 'arrive' => '06:50', 'offset' => 1, 'price' => 450000,
        ],
        [
            'route' => ['Jakarta', 'Solo'], 'duration' => 540, 'bus' => 'BUS-53-166',
            'from' => 'Terminal Pulo Gebang', 'to' => 'Terminal Tirtonadi',
            'depart' => '19:30', 'arrive' => '04:30', 'offset' => 1, 'price' => 210000,
        ],
        [
            'route' => ['Jakarta', 'Bandung'], 'duration' => 180, 'bus' => 'BUS-42-301',
            'from' => 'Terminal Kp. Rambutan', 'to' => 'Terminal Cicaheum',
            'depart' => '08:00', 'arrive' => '11:00', 'offset' => 0, 'price' => 120000,
        ],
        [
            'route' => ['Surabaya', 'Yogyakarta'], 'duration' => 360, 'bus' => 'BUS-42-101',
            'from' => 'Terminal Purabaya Bungurasih', 'to' => 'Terminal Giwangan',
            'depart' => '09:00', 'arrive' => '15:00', 'offset' => 0, 'price' => 180000,
        ],
        [
            'route' => ['Jakarta', 'Malang'], 'duration' => 900, 'bus' => 'BUS-58-772',
            'from' => 'Terminal Pulo Gebang', 'to' => 'Terminal Arjosari',
            'depart' => '14:00', 'arrive' => '05:00', 'offset' => 1, 'price' => 350000,
        ],
        [
            'route' => ['Bandung', 'Surabaya'], 'duration' => 720, 'bus' => 'BUS-53-208',
            'from' => 'Terminal Cicaheum', 'to' => 'Terminal Purabaya Bungurasih',
            'depart' => '21:00', 'arrive' => '09:00', 'offset' => 1, 'price' => 320000,
        ],
        [
            'route' => ['Surabaya', 'Jakarta'], 'duration' => 690, 'bus' => 'BUS-42-215',
            'from' => 'Terminal Purabaya Bungurasih', 'to' => 'Terminal Pulo Gebang',
            'depart' => '20:00', 'arrive' => '07:00', 'offset' => 1, 'price' => 375000,
        ],
    ];

    private array $passengerNames = [
        'Agus Setiawan', 'Dewi Lestari', 'Bambang Sutrisno', 'Siti Marlina', 'Rudi Hartono',
        'Nur Aisyah', 'Joko Widarto', 'Rina Wulandari', 'Hendra Gunawan', 'Fitri Handayani',
        'Dedi Kurniawan', 'Lestari Ningsih', 'Toko Saputra', 'Maya Anggraini', 'Andi Pratama',
        'Wulan Sari', 'Bayu Nugroho', 'Sri Wahyuni', 'Fajar Ramadhan', 'Indah Permata',
    ];

    public function run(): void
    {
        $this->createRoutes();

        foreach ($this->services as $service) {
            foreach (range(-3, 6) as $dayOffset) {
                $this->createSchedule($service, $dayOffset);
            }
        }

        $this->fillOccupancy();
    }

    private function createRoutes(): void
    {
        foreach ($this->services as $service) {
            $key = $service['route'][0].'->'.$service['route'][1];
            if (isset($this->routes[$key])) {
                continue;
            }

            $origin = City::where('name', $service['route'][0])->first();
            $destination = City::where('name', $service['route'][1])->first();

            $this->routes[$key] = BusRoute::firstOrCreate(
                ['origin_city_id' => $origin->id, 'destination_city_id' => $destination->id],
                ['duration_minutes' => $service['duration']]
            );
        }
    }

    private function createSchedule(array $service, int $dayOffset): Schedule
    {
        $key = $service['route'][0].'->'.$service['route'][1];
        $bus = Bus::where('code', $service['bus'])->first();

        $serviceDate = now()->addDays($dayOffset);

        $schedule = Schedule::create([
            'route_id' => $this->routes[$key]->id,
            'bus_id' => $bus->id,
            'operator_id' => $bus->operator_id,
            'departure_terminal_id' => Terminal::where('name', $service['from'])->first()->id,
            'arrival_terminal_id' => Terminal::where('name', $service['to'])->first()->id,
            'service_date' => $serviceDate->toDateString(),
            'departure_time' => $service['depart'],
            'arrival_time' => $service['arrive'],
            'arrival_day_offset' => $service['offset'],
            'price' => $service['price'],
            'status' => $serviceDate->isPast() ? 'completed' : 'scheduled',
        ]);

        $sequence = 1;
        $schedule->stops()->create([
            'sequence' => $sequence++,
            'name' => $service['from'],
            'stop_time' => $service['depart'],
            'note' => 'Titik awal keberangkatan',
            'is_terminal' => true,
        ]);

        foreach ($service['stops'] ?? [] as $stop) {
            $schedule->stops()->create([
                'sequence' => $sequence++,
                'name' => $stop['name'],
                'stop_time' => $stop['time'],
                'note' => $stop['note'],
                'is_terminal' => false,
            ]);
        }

        $schedule->stops()->create([
            'sequence' => $sequence++,
            'name' => $service['to'],
            'stop_time' => $service['arrive'],
            'note' => 'Tujuan akhir perjalanan',
            'is_terminal' => true,
        ]);

        return $schedule;
    }

    private function fillOccupancy(): void
    {
        $methods = ['qris', 'va', 'ewallet'];

        $todaySchedules = Schedule::whereDate('service_date', today())
            ->where('status', '!=', 'cancelled')
            ->get();

        foreach ($todaySchedules as $schedule) {
            $seats = $schedule->bus->seats()->pluck('seat_no')->shuffle()->values();
            $target = (int) floor($seats->count() * (0.4 + mt_rand(0, 45) / 100));

            foreach ($seats->slice(0, $target) as $seatNo) {
                $this->createPaidBooking($schedule, $seatNo, collect($this->passengerNames)->random(), $methods[array_rand($methods)]);
            }
        }
    }

    private function createPaidBooking(Schedule $schedule, string $seatNo, string $name, string $method, ?int $userId = null): void
    {
        $insuranceFee = Setting::int('insurance_fee', 5000);
        $subtotal = $schedule->price;
        $total = $subtotal + $insuranceFee;

        $booking = Booking::create([
            'code' => Booking::generateCode(),
            'user_id' => $userId,
            'schedule_id' => $schedule->id,
            'contact_name' => $name,
            'contact_phone' => '+62 8'.random_int(1000000000, 9999999999),
            'contact_email' => str_replace(' ', '.', strtolower($name)).'@email.com',
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
            'paid_at' => now()->subMinutes(mt_rand(20, 300)),
        ]);

        $passenger = $booking->passengers()->create([
            'schedule_id' => $schedule->id,
            'seat_no' => $seatNo,
            'full_name' => $name,
            'nik' => random_int(1000000000000000, 9999999999999999),
            'status' => 'active',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'method' => $method,
            'amount' => $total,
            'status' => 'paid',
            'provider' => 'mock',
            'reference' => strtoupper($method.'-'.random_int(10000000, 99999999)),
            'bank' => $method === 'va' ? ['BCA', 'BRI', 'MANDIRI', 'BNI'][array_rand(['BCA', 'BRI', 'MANDIRI', 'BNI'])] : null,
            'qr_payload' => $method === 'qris' ? 'QRISMOCK-'.$booking->code : null,
            'expires_at' => now()->addMinutes(15),
            'paid_at' => $booking->paid_at,
        ]);

        unset($passenger);
    }
}
