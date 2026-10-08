<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const KEYS = [
        'service_fee',
        'insurance_fee',
        'protection_fee',
        'protection_enabled',
        'booking_expiry_minutes',
        'boarding_lead_minutes',
    ];

    public function index(): View
    {
        $settings = collect(self::KEYS)->mapWithKeys(
            fn ($key) => [$key => Setting::get($key, '')]
        );

        return view('pages.admin.settings', ['settings' => $settings]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'service_fee' => ['required', 'integer', 'min:0'],
            'insurance_fee' => ['required', 'integer', 'min:0'],
            'protection_fee' => ['required', 'integer', 'min:0'],
            'protection_enabled' => ['required', 'in:0,1'],
            'booking_expiry_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'boarding_lead_minutes' => ['required', 'integer', 'min:0', 'max:240'],
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('status', 'Pengaturan berhasil disimpan.');
    }
}
