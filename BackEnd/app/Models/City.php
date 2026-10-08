<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = ['name', 'province'];

    public function terminals(): HasMany
    {
        return $this->hasMany(Terminal::class);
    }

    public function originRoutes(): HasMany
    {
        return $this->hasMany(BusRoute::class, 'origin_city_id');
    }

    public function destinationRoutes(): HasMany
    {
        return $this->hasMany(BusRoute::class, 'destination_city_id');
    }
}
