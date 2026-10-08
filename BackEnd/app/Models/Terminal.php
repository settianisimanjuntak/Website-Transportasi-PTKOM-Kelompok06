<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Terminal extends Model
{
    protected $fillable = ['city_id', 'name', 'code'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
