<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::query()
            ->with(['user', 'schedule.bus.operator', 'schedule.route.originCity', 'schedule.route.destinationCity', 'payment'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->string('q');
                $query->where(function ($sub) use ($q) {
                    $sub->where('code', 'like', "%{$q}%")
                        ->orWhere('contact_name', 'like', "%{$q}%")
                        ->orWhere('contact_phone', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.bookings.index', [
            'bookings' => $bookings,
            'filters' => [
                'status' => $request->string('status'),
                'q' => $request->string('q'),
            ],
        ]);
    }

    public function show(Booking $booking): View
    {
        PaymentService::expireIfNeeded($booking);

        $booking->refresh()->load([
            'user', 'payment', 'passengers.ticket',
            'schedule.bus.operator', 'schedule.bus.busClass',
            'schedule.route.originCity', 'schedule.route.destinationCity',
            'schedule.departureTerminal', 'schedule.arrivalTerminal', 'schedule.stops',
        ]);

        return view('pages.admin.bookings.show', [
            'booking' => $booking,
        ]);
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya pemesanan berstatus pending yang dapat dibatalkan.');
        }

        PaymentService::expireIfNeeded($booking);
        $booking->refresh();

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Pemesanan sudah berakhir dan tidak dapat dibatalkan manual.');
        }

        $booking->passengers()->where('status', 'active')->update(['status' => 'released', 'seat_no' => null]);
        $booking->update(['status' => 'cancelled']);
        $booking->payment?->update(['status' => 'failed']);

        return back()->with('status', 'Pemesanan '.$booking->code.' dibatalkan dan kursi dikembalikan.');
    }
}
