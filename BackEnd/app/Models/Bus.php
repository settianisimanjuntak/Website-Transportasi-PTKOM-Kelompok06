<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    protected $fillable = [
        'operator_id', 'bus_class_id', 'code', 'model', 'capacity', 'layout', 'photo', 'status',
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function busClass(): BelongsTo
    {
        return $this->belongsTo(BusClass::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'bus_facility');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function generateSeats(): void
    {
        $this->seats()->delete();

        $parts = array_map('intval', explode('-', $this->layout));
        $perRow = array_sum($parts) ?: 4;
        $rows = (int) ceil($this->capacity / $perRow);
        $letters = ['A', 'B', 'C', 'D', 'E', 'F'];

        $created = 0;
        for ($row = 1; $row <= $rows && $created < $this->capacity; $row++) {
            foreach (array_slice($letters, 0, $perRow) as $index => $letter) {
                if ($created >= $this->capacity) {
                    break;
                }
                $seatNo = $row.$letter;
                $this->seats()->create([
                    'seat_no' => $seatNo,
                    'seat_row' => $row,
                    'seat_col' => $letter,
                    'is_window' => $index === 0 || $index === $perRow - 1,
                ]);
                $created++;
            }
        }
    }
}
