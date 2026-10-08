<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'code', 'user_id', 'schedule_id', 'contact_name', 'contact_phone', 'contact_email',
        'is_self_passenger', 'protection', 'protection_fee', 'insurance_fee', 'service_fee',
        'subtotal', 'total', 'payment_method', 'status', 'expires_at', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'is_self_passenger' => 'boolean',
            'protection' => 'boolean',
            'expires_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function belongsToUser(?User $user): bool
    {
        return $user !== null && $this->user_id === $user->id;
    }

    public function isExpired(): bool
    {
        return $this->status === 'pending'
            && $this->expires_at !== null
            && $this->expires_at->isPast();
    }

    public static function generateCode(): string
    {
        do {
            $code = 'PNR-'.random_int(100000000, 999999999);
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
