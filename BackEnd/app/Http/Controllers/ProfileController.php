<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        $bookings = $user->bookings()
            ->with([
                'schedule.bus.operator', 'schedule.bus.busClass',
                'schedule.route.originCity', 'schedule.route.destinationCity',
                'schedule.departureTerminal', 'schedule.arrivalTerminal',
                'schedule.stops', 'passengers',
            ])
            ->orderByDesc('id')
            ->get();

        $upcoming = $bookings->filter(
            fn ($booking) => $booking->status === 'paid'
                && $booking->schedule
                && $booking->schedule->service_date->gte(today())
        );

        $history = $bookings->filter(
            fn ($booking) => $booking->status === 'paid'
                && $booking->schedule
                && $booking->schedule->service_date->lt(today())
        )->take(10);

        return view('pages.profile', [
            'user' => $user,
            'upcoming' => $upcoming,
            'history' => $history,
            'stats' => [
                'trips' => $bookings->where('status', 'paid')->count(),
                'active' => $upcoming->count(),
                'points' => $user->points,
            ],
            'insuranceFee' => Setting::int('insurance_fee', 5000),
            'serviceFee' => Setting::int('service_fee', 0),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['required', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'gender' => $this->genderRule(),
        ]);

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('status', 'Perubahan data diri berhasil disimpan.');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => $request->input('password'),
        ]);

        return back()->with('status', 'Kata sandi berhasil diperbarui.');
    }
}
