<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Setting;
use App\Services\PaymentService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function seat(Request $request, Schedule $schedule): View|RedirectResponse
    {
        if (! $schedule->isBookable()) {
            return redirect()->route('results')->with('error', 'Jadwal ini tidak dapat dipesan (penuh, dibatalkan, atau sudah lewat).');
        }

        $schedule->load(['route.originCity', 'route.destinationCity', 'bus.operator', 'bus.busClass', 'departureTerminal', 'arrivalTerminal']);

        $seats = $schedule->bus->seats()->orderBy('seat_row')->orderBy('seat_col')->get();
        $booked = $schedule->passengers()->pluck('seat_no')->all();
        $user = $request->user();

        $insuranceFee = Setting::int('insurance_fee', 5000);
        $serviceFee = Setting::int('service_fee', 0);
        $protectionFee = Setting::int('protection_fee', 10000);

        return view('pages.seat', [
            'schedule' => $schedule,
            'seats' => $seats,
            'booked' => $booked,
            'user' => $user,
            'insuranceFee' => $insuranceFee,
            'serviceFee' => $serviceFee,
            'protectionFee' => $protectionFee,
            'protectionEnabled' => Setting::get('protection_enabled') === '1',
            'baseTotal' => $schedule->price + $insuranceFee + $serviceFee,
        ]);
    }

    public function store(Request $request, Schedule $schedule): RedirectResponse
    {
        $data = $request->validate([
            'seat_no' => ['required', 'string', 'max:5'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'contact_email' => ['required', 'email', 'max:255'],
            'is_self' => ['nullable', 'boolean'],
            'full_name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            'protection' => ['nullable', 'boolean'],
            'payment_method' => ['required', 'in:qris,va,ewallet'],
        ]);

        if (! $schedule->isBookable()) {
            return back()->withInput()->with('error', 'Jadwal ini tidak dapat dipesan (penuh, dibatalkan, atau sudah lewat).');
        }

        $seatNo = strtoupper($data['seat_no']);

        if (! $schedule->bus->seats()->where('seat_no', $seatNo)->exists()) {
            return back()->withInput()->withErrors(['seat_no' => 'Kursi tidak tersedia pada armada ini.']);
        }

        $taken = $schedule->passengers()->where('seat_no', $seatNo)->exists();
        if ($taken) {
            return back()->withInput()->withErrors(['seat_no' => 'Kursi ini baru saja dipesan penumpang lain.']);
        }

        $insuranceFee = Setting::int('insurance_fee', 5000);
        $serviceFee = Setting::int('service_fee', 0);
        $protectionFee = Setting::int('protection_fee', 10000);
        $withProtection = $request->boolean('protection') && Setting::get('protection_enabled') === '1';

        $protectionCost = $withProtection ? $protectionFee : 0;
        $subtotal = $schedule->price + $insuranceFee;
        $total = $subtotal + $protectionCost + $serviceFee;

        try {
            $booking = DB::transaction(function () use ($data, $schedule, $seatNo, $insuranceFee, $serviceFee, $protectionCost, $subtotal, $total) {
                $booking = Booking::create([
                    'code' => Booking::generateCode(),
                    'user_id' => auth()->id(),
                    'schedule_id' => $schedule->id,
                    'contact_name' => $data['contact_name'],
                    'contact_phone' => $data['contact_phone'],
                    'contact_email' => $data['contact_email'],
                    'is_self_passenger' => (bool) ($data['is_self'] ?? false),
                    'protection' => $protectionCost > 0,
                    'protection_fee' => $protectionCost,
                    'insurance_fee' => $insuranceFee,
                    'service_fee' => $serviceFee,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'payment_method' => $data['payment_method'],
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(PaymentService::expiryMinutes()),
                ]);

                $booking->passengers()->create([
                    'schedule_id' => $schedule->id,
                    'seat_no' => $seatNo,
                    'full_name' => $data['full_name'],
                    'nik' => $data['nik'],
                    'status' => 'active',
                ]);

                PaymentService::createForBooking($booking, $data['payment_method']);

                return $booking;
            });
        } catch (UniqueConstraintViolationException) {
            return back()->withInput()->withErrors(['seat_no' => 'Kursi ini baru saja dipesan penumpang lain. Silakan pilih kursi lain.']);
        }

        return redirect()->route('payment.show', $booking)
            ->with('status', 'Kursi berhasil dipesan. Selesaikan pembayaran dalam '.PaymentService::expiryMinutes().' menit.');
    }
}
