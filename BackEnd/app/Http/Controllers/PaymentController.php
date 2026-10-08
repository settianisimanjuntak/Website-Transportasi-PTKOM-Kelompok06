<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\PaymentService;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(Request $request, Booking $booking): View|RedirectResponse
    {
        $this->authorizeBooking($request, $booking);

        PaymentService::expireIfNeeded($booking);
        $booking->refresh()->load(['schedule.bus.operator', 'schedule.route.originCity', 'schedule.route.destinationCity', 'payment']);

        if ($booking->status === 'paid') {
            return redirect()->route('tickets.index', $booking);
        }

        $payment = $booking->payment;

        return view('pages.payment', [
            'booking' => $booking,
            'payment' => $payment,
            'qrImage' => $payment && $payment->method === 'qris' && $payment->qr_payload
                ? QrCodeService::dataUri($payment->qr_payload)
                : null,
            'secondsLeft' => $booking->expires_at ? max(0, now()->diffInSeconds($booking->expires_at, false)) : 0,
            'banks' => ['BCA', 'BRI', 'MANDIRI', 'BNI'],
            'ewallets' => ['ShopeePay', 'DANA'],
        ]);
    }

    public function simulate(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorizeBooking($request, $booking);

        PaymentService::expireIfNeeded($booking);
        $booking->refresh();

        if ($booking->status === 'paid') {
            return redirect()->route('tickets.index', $booking);
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Pembayaran tidak dapat diproses (status: '.$booking->status.').');
        }

        PaymentService::markPaid($booking);

        return redirect()->route('tickets.index', $booking)
            ->with('status', 'Pembayaran berhasil! E-tiket Anda sudah diterbitkan.');
    }

    public function status(Request $request, Booking $booking): JsonResponse
    {
        $this->authorizeBooking($request, $booking);

        PaymentService::expireIfNeeded($booking);
        $booking->refresh();

        return response()->json([
            'booking_status' => $booking->status,
            'payment_status' => $booking->payment?->status,
        ]);
    }
}
