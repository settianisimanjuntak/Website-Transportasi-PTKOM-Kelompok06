<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->filters($request);
        $cities = City::orderBy('name')->get();

        $hasQuery = $request->filled('from') || $request->filled('to') || $request->filled('date');

        if (! $hasQuery) {
            $filters['date'] = null;
        }

        $query = $this->schedulesQuery($filters);

        if (! $hasQuery) {
            $query->whereDate('service_date', '>=', today());
        }

        $schedules = $query->take(3)->get();

        $fromCity = City::find($filters['from']);
        $toCity = City::find($filters['to']);

        return view('pages.search', [
            'cities' => $cities,
            'schedules' => $schedules,
            'filters' => $filters,
            'fromCity' => $fromCity,
            'toCity' => $toCity,
            'hasQuery' => $hasQuery,
            'resultCount' => $schedules->count(),
            'heading' => $fromCity && $toCity
                ? $fromCity->name.' → '.$toCity->name
                : 'Jadwal Terbaru',
            'dateLabel' => $this->dateLabel($filters['date'] ?? null),
        ]);
    }

    public function results(Request $request): View
    {
        $filters = $this->filters($request, withDate: true);
        $cities = City::orderBy('name')->get();

        $schedules = $this->schedulesQuery($filters)->get();

        $fromCity = City::find($filters['from']);
        $toCity = City::find($filters['to']);

        return view('pages.results', [
            'cities' => $cities,
            'schedules' => $schedules,
            'filters' => $filters,
            'fromCity' => $fromCity,
            'toCity' => $toCity,
            'resultCount' => $schedules->count(),
            'heading' => $fromCity && $toCity
                ? $fromCity->name.' → '.$toCity->name
                : 'Semua Rute',
            'dateLabel' => $this->dateLabel($filters['date']),
        ]);
    }

    private function filters(Request $request, bool $withDate = false): array
    {
        $data = $request->validate([
            'from' => ['nullable', 'integer', 'exists:cities,id'],
            'to' => ['nullable', 'integer', 'exists:cities,id'],
            'date' => ['nullable', 'date'],
            'filter' => ['nullable', 'string', 'in:all,pagi,malam,exec'],
            'sort' => ['nullable', 'string', 'in:murah,cepat,awal'],
        ]);

        return [
            'from' => isset($data['from']) ? (int) $data['from'] : null,
            'to' => isset($data['to']) ? (int) $data['to'] : null,
            'date' => $data['date'] ?? ($withDate ? today()->toDateString() : null),
            'filter' => $data['filter'] ?? 'all',
            'sort' => $data['sort'] ?? 'awal',
        ];
    }

    private function schedulesQuery(array $filters)
    {
        $query = Schedule::query()
            ->available()
            ->with([
                'route.originCity', 'route.destinationCity',
                'bus.operator', 'bus.busClass',
                'departureTerminal', 'arrivalTerminal', 'stops',
            ])
            ->withCount(['passengers as booked_count']);

        if ($filters['from']) {
            $query->whereHas('route', fn ($q) => $q->where('origin_city_id', $filters['from']));
        }

        if ($filters['to']) {
            $query->whereHas('route', fn ($q) => $q->where('destination_city_id', $filters['to']));
        }

        if ($filters['date']) {
            $query->whereDate('service_date', $filters['date']);
        }

        match ($filters['filter']) {
            'pagi' => $query->whereBetween('departure_time', ['06:00:00', '12:00:00']),
            'malam' => $query->where('departure_time', '>=', '18:00:00'),
            'exec' => $query->whereHas('bus.busClass', fn ($q) => $q->whereIn('slug', [
                'executive', 'executive-plus', 'sleeper', 'suite-class', 'super-luxury',
            ])),
            default => null,
        };

        if ($filters['sort'] === 'murah') {
            $query->orderBy('price');
        } elseif ($filters['sort'] === 'cepat') {
            $query->leftJoin('routes', 'routes.id', '=', 'schedules.route_id')
                ->orderBy('routes.duration_minutes');
        } else {
            $query->orderBy('departure_time');
        }

        return $query->orderBy('schedules.service_date')->orderBy('schedules.departure_time');
    }

    private function dateLabel(?string $date): string
    {
        $carbon = $date ? \Illuminate\Support\Carbon::parse($date) : today();

        return $carbon->locale('id')->translatedFormat('l, d F Y');
    }
}
