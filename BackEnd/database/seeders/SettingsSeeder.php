<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'service_fee' => '0',
            'insurance_fee' => '5000',
            'protection_fee' => '10000',
            'protection_enabled' => '1',
            'booking_expiry_minutes' => '15',
            'boarding_lead_minutes' => '30',
        ];

        foreach ($defaults as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
