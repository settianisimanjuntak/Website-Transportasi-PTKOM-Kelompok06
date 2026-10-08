<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\BusClass;
use App\Models\City;
use App\Models\Facility;
use App\Models\Operator;
use App\Models\Policy;
use App\Models\Terminal;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'Jakarta', 'province' => 'DKI Jakarta'],
            ['name' => 'Bandung', 'province' => 'Jawa Barat'],
            ['name' => 'Surabaya', 'province' => 'Jawa Timur'],
            ['name' => 'Yogyakarta', 'province' => 'DI Yogyakarta'],
            ['name' => 'Solo', 'province' => 'Jawa Tengah'],
            ['name' => 'Malang', 'province' => 'Jawa Timur'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }

        $terminals = [
            ['city' => 'Jakarta', 'name' => 'Terminal Pulo Gebang', 'code' => 'JKT-PG'],
            ['city' => 'Jakarta', 'name' => 'Terminal Kp. Rambutan', 'code' => 'JKT-KR'],
            ['city' => 'Jakarta', 'name' => 'Cirogol', 'code' => 'JKT-CG'],
            ['city' => 'Bandung', 'name' => 'Terminal Cicaheum', 'code' => 'BDG-CC'],
            ['city' => 'Bandung', 'name' => 'Terminal Leuwipanjang', 'code' => 'BDG-LP'],
            ['city' => 'Surabaya', 'name' => 'Terminal Purabaya Bungurasih', 'code' => 'SBY-PB'],
            ['city' => 'Yogyakarta', 'name' => 'Terminal Giwangan', 'code' => 'YGY-GW'],
            ['city' => 'Yogyakarta', 'name' => 'Terminal Jombor', 'code' => 'YGY-JB'],
            ['city' => 'Solo', 'name' => 'Terminal Tirtonadi', 'code' => 'SOL-TD'],
            ['city' => 'Malang', 'name' => 'Terminal Arjosari', 'code' => 'MLG-AR'],
        ];

        foreach ($terminals as $terminal) {
            $city = City::where('name', $terminal['city'])->first();
            $city->terminals()->create([
                'name' => $terminal['name'],
                'code' => $terminal['code'],
            ]);
        }

        $classes = [
            ['name' => 'Executive', 'slug' => 'executive', 'description' => 'Executive 2-2 · AC, Toilet', 'seat_layout' => '2-2', 'badge' => 'green'],
            ['name' => 'Executive Plus', 'slug' => 'executive-plus', 'description' => 'Executive Plus 2-2 · AC, Toilet, Snack', 'seat_layout' => '2-2', 'badge' => 'green'],
            ['name' => 'VIP', 'slug' => 'vip', 'description' => 'VIP 2-2 · AC, Port USB', 'seat_layout' => '2-2', 'badge' => 'amber'],
            ['name' => 'Sleeper', 'slug' => 'sleeper', 'description' => 'Sleeper Class · Flat Bed, AVOD', 'seat_layout' => '2-2', 'badge' => 'amber'],
            ['name' => 'Suite Class', 'slug' => 'suite-class', 'description' => 'Suite Class · Flat Bed, AVOD', 'seat_layout' => '2-2', 'badge' => 'green'],
            ['name' => 'Super Luxury', 'slug' => 'super-luxury', 'description' => 'Sleeper Class · Flat Bed, AVOD', 'seat_layout' => '2-2', 'badge' => 'green'],
            ['name' => 'Green Platinum', 'slug' => 'green-platinum', 'description' => 'Green Platinum 2-1 · AC, Legrest', 'seat_layout' => '2-1', 'badge' => 'green'],
        ];

        foreach ($classes as $class) {
            BusClass::create($class);
        }

        $facilities = [
            ['name' => 'Full AC Dingin', 'icon' => '❄️'],
            ['name' => 'Reclining & Legrest', 'icon' => '🛋️'],
            ['name' => 'Bantal & Selimut', 'icon' => '🛏️'],
            ['name' => 'USB Charger', 'icon' => '🔌'],
            ['name' => 'Wi-Fi Onboard', 'icon' => '📶'],
            ['name' => 'AVOD Pribadi', 'icon' => '🎬'],
            ['name' => 'Makan & Snack', 'icon' => '🍱'],
            ['name' => 'Toilet Bersih', 'icon' => '🚻'],
            ['name' => 'Bagasi 20kg', 'icon' => '🧳'],
            ['name' => 'Snack & Air Mineral', 'icon' => '🍪'],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }

        $operators = [
            ['name' => 'PO Rosalia Indah', 'code' => 'ROSA', 'email' => 'cs@rosaliaindah.co.id', 'phone' => '0218234567', 'address' => 'Jakarta Timur'],
            ['name' => 'PO Sinar Jaya', 'code' => 'SINJ', 'email' => 'cs@sinarjayagroup.com', 'phone' => '0218234568', 'address' => 'Bekasi, Jawa Barat'],
            ['name' => 'PO Harapan Jaya', 'code' => 'HPJY', 'email' => 'cs@harapanjaya.com', 'phone' => '0218234569', 'address' => 'Surabaya, Jawa Timur'],
            ['name' => 'PO Gunung Harta', 'code' => 'GNHT', 'email' => 'cs@gunungharta.com', 'phone' => '0218234570', 'address' => 'Malang, Jawa Timur'],
            ['name' => 'PO Juragan 99', 'code' => 'JG99', 'email' => 'cs@juragan99.com', 'phone' => '0218234571', 'address' => 'Surabaya, Jawa Timur'],
        ];

        foreach ($operators as $operator) {
            Operator::create($operator);
        }

        foreach (Operator::all() as $operator) {
            Policy::create([
                'operator_id' => $operator->id,
                'reschedule_text' => 'Pengajuan perubahan waktu paling lambat 12 jam sebelum keberangkatan via aplikasi. Dikenakan biaya administrasi PO 10%.',
                'reschedule_fee_percent' => 10,
                'cancel_text' => 'Pengembalian dana 75% jika dibatalkan minimal 24 jam sebelum keberangkatan. Di bawah 24 jam tiket hangus sesuai regulasi operator bus.',
                'refund_percent' => 75,
                'refund_min_hours' => 24,
            ]);
        }

        $buses = [
            ['operator' => 'PO Rosalia Indah', 'class' => 'executive-plus', 'code' => 'BUS-42-329', 'model' => 'Scania K360IB', 'capacity' => 30, 'layout' => '2-2'],
            ['operator' => 'PO Rosalia Indah', 'class' => 'sleeper', 'code' => 'BUS-42-101', 'model' => 'Mercedes OH1626', 'capacity' => 28, 'layout' => '2-2'],
            ['operator' => 'PO Sinar Jaya', 'class' => 'suite-class', 'code' => 'BUS-53-166', 'model' => 'Hino RK8 Premium', 'capacity' => 22, 'layout' => '2-2'],
            ['operator' => 'PO Sinar Jaya', 'class' => 'vip', 'code' => 'BUS-53-208', 'model' => 'Mercedes OH1626', 'capacity' => 30, 'layout' => '2-2'],
            ['operator' => 'PO Harapan Jaya', 'class' => 'executive', 'code' => 'BUS-42-118', 'model' => 'Scania K360IB', 'capacity' => 30, 'layout' => '2-2'],
            ['operator' => 'PO Harapan Jaya', 'class' => 'super-luxury', 'code' => 'BUS-42-215', 'model' => 'Scania K410IB', 'capacity' => 28, 'layout' => '2-2'],
            ['operator' => 'PO Gunung Harta', 'class' => 'green-platinum', 'code' => 'BUS-58-772', 'model' => 'Mercedes O500', 'capacity' => 32, 'layout' => '2-1'],
            ['operator' => 'PO Juragan 99', 'class' => 'sleeper', 'code' => 'BUS-280-84', 'model' => 'Scania K360IB', 'capacity' => 18, 'layout' => '2-2'],
            ['operator' => 'PO Harapan Jaya', 'class' => 'executive', 'code' => 'BUS-42-301', 'model' => 'Hino RK8', 'capacity' => 30, 'layout' => '2-2'],
            ['operator' => 'PO Gunung Harta', 'class' => 'executive', 'code' => 'BUS-58-773', 'model' => 'Mercedes OH1626', 'capacity' => 30, 'layout' => '2-2'],
        ];

        $fullFacilities = Facility::pluck('id', 'name');

        foreach ($buses as $data) {
            $operator = Operator::where('name', $data['operator'])->first();
            $class = BusClass::where('slug', $data['class'])->first();

            $bus = Bus::create([
                'operator_id' => $operator->id,
                'bus_class_id' => $class->id,
                'code' => $data['code'],
                'model' => $data['model'],
                'capacity' => $data['capacity'],
                'layout' => $data['layout'],
                'status' => 'operational',
            ]);

            $bus->generateSeats();

            $wanted = match ($class->slug) {
                'suite-class', 'sleeper', 'super-luxury' => ['Full AC Dingin', 'Reclining & Legrest', 'Bantal & Selimut', 'USB Charger', 'Wi-Fi Onboard', 'AVOD Pribadi', 'Makan & Snack', 'Toilet Bersih', 'Bagasi 20kg'],
                'vip' => ['Full AC Dingin', 'Reclining & Legrest', 'USB Charger', 'Snack & Air Mineral', 'Toilet Bersih', 'Bagasi 20kg'],
                'green-platinum' => ['Full AC Dingin', 'Reclining & Legrest', 'USB Charger', 'Snack & Air Mineral', 'Bagasi 20kg'],
                default => ['Full AC Dingin', 'Reclining & Legrest', 'USB Charger', 'Wi-Fi Onboard', 'Snack & Air Mineral', 'Toilet Bersih', 'Bagasi 20kg'],
            };

            foreach ($wanted as $name) {
                if (isset($fullFacilities[$name])) {
                    $bus->facilities()->attach($fullFacilities[$name]);
                }
            }
        }
    }
}
