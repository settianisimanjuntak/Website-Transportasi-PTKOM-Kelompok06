<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = ['booking_id', 'booking_passenger_id', 'code', 'qr_payload', 'status', 'issued_at'];

    protected function casts(): array
    {
        return ['issued_at' => 'datetime'];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(BookingPassenger::class, 'booking_passenger_id');
    }

    public static function generateCode(): string
    {
        do {
            $code = 'TK-'.random_int(1000, 9999).'-'.date('Y');
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
