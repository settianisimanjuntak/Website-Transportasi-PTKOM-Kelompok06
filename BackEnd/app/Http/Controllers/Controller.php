<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

abstract class Controller
{
    protected function authorizeBooking(Request $request, Booking $booking): void
    {
        $user = $request->user();

        abort_unless($user && ($booking->user_id === $user->id || $user->isAdmin()), 403);
    }

    protected function authorizeTicket(Request $request, Ticket $ticket): void
    {
        $user = $request->user();

        abort_unless($user && ($ticket->booking->user_id === $user->id || $user->isAdmin()), 403);
    }

    protected function genderRule(): array
    {
        return ['nullable', Rule::in(['male', 'female'])];
    }
}
