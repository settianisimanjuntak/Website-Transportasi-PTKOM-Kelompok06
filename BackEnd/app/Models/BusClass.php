<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusClass extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'seat_layout', 'badge'];

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }
}
