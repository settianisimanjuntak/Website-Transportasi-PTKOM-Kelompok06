<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Operator extends Model
{
    protected $fillable = ['name', 'code', 'email', 'phone', 'address', 'description', 'logo'];

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function policy(): HasOne
    {
        return $this->hasOne(Policy::class);
    }
}
