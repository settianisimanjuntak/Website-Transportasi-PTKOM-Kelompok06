<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'route_id', 'bus_id', 'operator_id', 'departure_terminal_id', 'arrival_terminal_id',
        'service_date', 'departure_time', 'arrival_time', 'arrival_day_offset', 'price', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'price' => 'integer',
            'arrival_day_offset' => 'integer',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(BusRoute::class, 'route_id');
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function departureTerminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'departure_terminal_id');
    }

    public function arrivalTerminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'arrival_terminal_id');
    }

    public function stops(): HasMany
    {
        return $this->hasMany(ScheduleStop::class)->orderBy('sequence');
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(BookingPassenger::class)->where('status', 'active');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function bookedCount(): int
    {
        return $this->passengers()->count();
    }

    public function remainingSeats(): int
    {
        return max(0, (int) ($this->bus?->capacity ?? 0) - $this->bookedCount());
    }

    public function isFullyBooked(): bool
    {
        return $this->remainingSeats() <= 0;
    }

    public function isBookable(): bool
    {
        return $this->status === 'scheduled'
            && $this->service_date->gte(today())
            && ! $this->isFullyBooked();
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public function durationText(): string
    {
        [$dh, $dm] = array_map('intval', explode(':', substr((string) $this->departure_time, 0, 5)));
        [$ah, $am] = array_map('intval', explode(':', substr((string) $this->arrival_time, 0, 5)));

        $minutes = ($ah * 60 + $am) - ($dh * 60 + $dm) + ($this->arrival_day_offset * 1440);
        if ($minutes < 0) {
            $minutes += 1440;
        }

        return sprintf('%dj %02dm', intdiv($minutes, 60), $minutes % 60);
    }

    public function arrivalText(): string
    {
        $time = \substr((string) $this->departure_time, 0, 5);

        return $time.($this->arrival_day_offset > 0 ? ' (+1)' : '');
    }
}
