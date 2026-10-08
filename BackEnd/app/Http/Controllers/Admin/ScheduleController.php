<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\Terminal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $schedules = Schedule::query()
            ->with(['bus.operator', 'bus.busClass', 'route.originCity', 'route.destinationCity', 'departureTerminal', 'arrivalTerminal'])
            ->withCount(['passengers as booked_count'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('service_date', $request->string('date')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->string('q');
                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('bus', fn ($bus) => $bus->where('code', 'like', "%{$q}%"))
                        ->orWhereHas('operator', fn ($op) => $op->where('name', 'like', "%{$q}%"));
                });
            })
            ->orderByDesc('service_date')
            ->orderBy('departure_time')
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.schedules.index', [
            'schedules' => $schedules,
            'filters' => [
                'status' => $request->string('status'),
                'date' => $request->string('date'),
                'q' => $request->string('q'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.schedules.form', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($request, $data) {
            $schedule = Schedule::create($data + ['status' => $request->input('status', 'scheduled')]);
            $this->syncStops($schedule, $request);
        });

        return redirect()->route('admin.schedules.index')->with('status', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule): View
    {
        $schedule->load('stops');

        return view('pages.admin.schedules.form', $this->formData($schedule));
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($request, $schedule, $data) {
            $schedule->update($data + ['status' => $request->input('status', $schedule->status)]);
            $schedule->stops()->delete();
            $this->syncStops($schedule, $request);
        });

        return redirect()->route('admin.schedules.index')->with('status', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        if ($schedule->passengers()->exists()) {
            return back()->with('error', 'Jadwal tidak dapat dihapus karena sudah ada pemesanan.');
        }

        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('status', 'Jadwal berhasil dihapus.');
    }

    public function show(Schedule $schedule): View
    {
        $schedule->load([
            'bus.operator', 'bus.busClass', 'route.originCity', 'route.destinationCity',
            'departureTerminal', 'arrivalTerminal', 'stops',
        ]);

        $passengers = $schedule->passengers()
            ->with(['booking.user', 'ticket'])
            ->orderBy('seat_no')
            ->get();

        return view('pages.admin.schedules.show', [
            'schedule' => $schedule,
            'passengers' => $passengers,
        ]);
    }

    private function formData(?Schedule $schedule = null): array
    {
        return [
            'schedule' => $schedule,
            'routes' => BusRoute::with(['originCity', 'destinationCity'])->get(),
            'buses' => Bus::with(['operator', 'busClass'])->where('status', '!=', 'retired')->orderBy('code')->get(),
            'terminals' => Terminal::with('city')->orderBy('name')->get(),
        ];
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'route_id' => ['required', 'exists:routes,id'],
            'bus_id' => ['required', 'exists:buses,id'],
            'departure_terminal_id' => ['required', 'exists:terminals,id'],
            'arrival_terminal_id' => ['required', 'exists:terminals,id'],
            'service_date' => ['required', 'date'],
            'departure_time' => ['required', 'date_format:H:i'],
            'arrival_time' => ['required', 'date_format:H:i'],
            'arrival_day_offset' => ['nullable', 'integer', 'min:0', 'max:3'],
            'price' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'in:scheduled,boarding,departed,completed,cancelled'],
            'stops' => ['nullable', 'array'],
            'stops.*.name' => ['required', 'string', 'max:255'],
            'stops.*.time' => ['nullable', 'date_format:H:i'],
            'stops.*.note' => ['nullable', 'string', 'max:255'],
        ]);

        $data['operator_id'] = Bus::whereKey($data['bus_id'])->value('operator_id');

        return $data;
    }

    private function syncStops(Schedule $schedule, Request $request): void
    {
        $stops = array_values(array_filter($request->input('stops', []), fn ($stop) => ! empty($stop['name'])));
        $count = count($stops);
        $sequence = 1;

        foreach ($stops as $stop) {
            $schedule->stops()->create([
                'sequence' => $sequence,
                'name' => $stop['name'],
                'stop_time' => ($stop['time'] ?? null) ? $stop['time'].':00' : null,
                'note' => $stop['note'] ?? null,
                'is_terminal' => $sequence === 1 || $sequence === $count,
            ]);
            $sequence++;
        }

        if ($count === 0) {
            $schedule->stops()->create([
                'sequence' => 1,
                'name' => $schedule->departureTerminal->name,
                'stop_time' => $schedule->departure_time,
                'note' => 'Titik awal keberangkatan',
                'is_terminal' => true,
            ]);
            $schedule->stops()->create([
                'sequence' => 2,
                'name' => $schedule->arrivalTerminal->name,
                'stop_time' => $schedule->arrival_time,
                'note' => 'Tujuan akhir perjalanan',
                'is_terminal' => true,
            ]);
        }
    }
}
