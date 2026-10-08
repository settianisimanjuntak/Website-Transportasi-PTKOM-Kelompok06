<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function show(Request $request, Schedule $schedule): View
    {
        abort_if($schedule->status === 'cancelled', 404);

        $schedule->load([
            'route.originCity', 'route.destinationCity',
            'bus.operator', 'bus.busClass', 'bus.facilities',
            'operator.policy', 'departureTerminal', 'arrivalTerminal', 'stops',
        ]);

        $insuranceFee = Setting::int('insurance_fee', 5000);
        $serviceFee = Setting::int('service_fee', 0);
        $protectionFee = Setting::int('protection_fee', 10000);

        return view('pages.detail', [
            'schedule' => $schedule,
            'insuranceFee' => $insuranceFee,
            'serviceFee' => $serviceFee,
            'protectionFee' => $protectionFee,
            'total' => $schedule->price + $insuranceFee + $serviceFee,
            'pax' => 1,
            'bookable' => $schedule->isBookable(),
        ]);
    }
}
