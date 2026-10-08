<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Operator;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $todaySchedules = Schedule::whereDate('service_date', today())
            ->where('status', '!=', 'cancelled');

        $totalToday = (clone $todaySchedules)->count();
        $nonCancelled = $totalToday;

        $occupiedToday = (clone $todaySchedules)->withCount(['passengers as booked_count'])
            ->get()
            ->sum('booked_count');

        $capacityToday = Schedule::query()
            ->whereDate('service_date', today())
            ->where('status', '!=', 'cancelled')
            ->with('bus:id,capacity')
            ->get()
            ->sum(fn ($schedule) => $schedule->bus?->capacity ?? 0);

        $schedules = Schedule::query()
            ->whereDate('service_date', today())
            ->where('status', '!=', 'cancelled')
            ->with(['bus.operator', 'bus.busClass', 'route.originCity', 'route.destinationCity', 'departureTerminal', 'arrivalTerminal'])
            ->withCount(['passengers as booked_count'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('operator_id'), fn ($query) => $query->where('operator_id', $request->integer('operator_id')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->string('q');
                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('bus', fn ($bus) => $bus->where('code', 'like', "%{$q}%"))
                        ->orWhereHas('operator', fn ($op) => $op->where('name', 'like', "%{$q}%"))
                        ->orWhereHas('route.originCity', fn ($city) => $city->where('name', 'like', "%{$q}%"))
                        ->orWhereHas('route.destinationCity', fn ($city) => $city->where('name', 'like', "%{$q}%"));
                });
            })
            ->orderBy('departure_time')
            ->paginate(8)
            ->withQueryString();

        return view('pages.admin', [
            'schedules' => $schedules,
            'operators' => Operator::orderBy('name')->get(),
            'kpis' => [
                'fleet' => Bus::where('status', 'operational')->count(),
                'occupancy' => $capacityToday > 0 ? round(($occupiedToday / $capacityToday) * 100, 1) : 0,
                'passengers' => $occupiedToday,
                'onTime' => $totalToday > 0 ? round(($nonCancelled / $totalToday) * 100, 1) : 100,
            ],
            'filters' => [
                'status' => $request->string('status'),
                'operator_id' => $request->integer('operator_id'),
                'q' => $request->string('q'),
            ],
        ]);
    }
}
