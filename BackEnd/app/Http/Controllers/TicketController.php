<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Setting;
use App\Models\Ticket;
use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request, Booking $booking): View|RedirectResponse
    {
        $this->authorizeBooking($request, $booking);

        $booking->load(['schedule.bus.operator', 'schedule.bus.facilities', 'schedule.route.originCity', 'schedule.route.destinationCity', 'schedule.departureTerminal', 'schedule.arrivalTerminal', 'passengers']);

        if ($booking->status !== 'paid') {
            return redirect()->route('payment.show', $booking);
        }

        $tickets = $booking->tickets()->with('passenger')->get();

        if ($tickets->isEmpty()) {
            return redirect()->route('payment.show', $booking)->with('error', 'E-tiket belum diterbitkan.');
        }

        return view('pages.eticket', [
            'booking' => $booking,
            'tickets' => $tickets,
            'qrImages' => $tickets->mapWithKeys(fn ($ticket) => [$ticket->id => QrCodeService::dataUri($ticket->qr_payload)]),
            'boardingLead' => Setting::int('boarding_lead_minutes', 30),
        ]);
    }

    public function show(Request $request, Ticket $ticket): View|RedirectResponse
    {
        $this->authorizeTicket($request, $ticket);

        return redirect()->route('tickets.index', $ticket->booking);
    }

    public function pdf(Request $request, Ticket $ticket): mixed
    {
        $this->authorizeTicket($request, $ticket);

        $ticket->load([
            'booking.schedule.bus.operator', 'booking.schedule.bus.facilities',
            'booking.schedule.route.originCity', 'booking.schedule.route.destinationCity',
            'booking.schedule.departureTerminal', 'booking.schedule.arrivalTerminal',
            'passenger',
        ]);

        $pdf = Pdf::loadView('tickets.pdf', [
            'ticket' => $ticket,
            'booking' => $ticket->booking,
            'schedule' => $ticket->booking->schedule,
            'qrImage' => QrCodeService::dataUri($ticket->qr_payload),
            'boardingLead' => Setting::int('boarding_lead_minutes', 30),
        ]);

        return $pdf->download('e-ticket-'.$ticket->code.'.pdf');
    }
}
