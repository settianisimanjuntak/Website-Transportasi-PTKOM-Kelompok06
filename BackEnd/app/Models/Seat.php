<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    protected $fillable = ['bus_id', 'seat_no', 'seat_row', 'seat_col', 'is_window'];

    protected function casts(): array
    {
        return ['is_window' => 'boolean'];
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }
}
