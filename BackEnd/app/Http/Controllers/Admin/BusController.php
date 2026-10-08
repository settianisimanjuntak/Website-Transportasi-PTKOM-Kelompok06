<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\BusClass;
use App\Models\Facility;
use App\Models\Operator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusController extends Controller
{
    public function index(Request $request): View
    {
        $buses = Bus::with(['operator', 'busClass'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->string('q');
                $query->where(function ($sub) use ($q) {
                    $sub->where('code', 'like', "%{$q}%")
                        ->orWhere('model', 'like', "%{$q}%")
                        ->orWhereHas('operator', fn ($op) => $op->where('name', 'like', "%{$q}%"));
                });
            })
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.buses.index', [
            'buses' => $buses,
            'q' => $request->string('q'),
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.buses.form', [
            'bus' => null,
            'operators' => Operator::orderBy('name')->get(),
            'classes' => BusClass::orderBy('name')->get(),
            'facilities' => Facility::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $bus = Bus::create($data);
        $bus->generateSeats();
        $bus->facilities()->sync($request->input('facilities', []));

        return redirect()->route('admin.buses.index')->with('status', 'Bus berhasil ditambahkan.');
    }

    public function edit(Bus $bus): View
    {
        $bus->load('facilities');

        return view('pages.admin.buses.form', [
            'bus' => $bus,
            'operators' => Operator::orderBy('name')->get(),
            'classes' => BusClass::orderBy('name')->get(),
            'facilities' => Facility::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Bus $bus): RedirectResponse
    {
        $data = $this->validated($request, $bus);

        $layoutOrCapacityChanged = $bus->capacity !== (int) $data['capacity']
            || $bus->layout !== $data['layout'];

        $bus->update($data);
        $bus->facilities()->sync($request->input('facilities', []));

        if ($layoutOrCapacityChanged) {
            $bus->generateSeats();
        }

        return redirect()->route('admin.buses.index')->with('status', 'Data bus berhasil diperbarui.');
    }

    public function destroy(Bus $bus): RedirectResponse
    {
        if ($bus->schedules()->exists()) {
            return back()->with('error', 'Bus tidak dapat dihapus karena masih terkait jadwal.');
        }

        $bus->facilities()->detach();
        $bus->delete();

        return redirect()->route('admin.buses.index')->with('status', 'Bus berhasil dihapus.');
    }

    private function validated(Request $request, ?Bus $bus = null): array
    {
        return $request->validate([
            'operator_id' => ['required', 'exists:operators,id'],
            'bus_class_id' => ['required', 'exists:bus_classes,id'],
            'code' => ['required', 'string', 'max:20', 'unique:buses,code'.($bus ? ','.$bus->id : '')],
            'model' => ['nullable', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1', 'max:60'],
            'layout' => ['required', 'string', 'max:10'],
            'photo' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:operational,maintenance,retired'],
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['exists:facilities,id'],
        ]);
    }
}
