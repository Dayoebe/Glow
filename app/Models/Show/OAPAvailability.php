<?php

namespace App\Models\Show;

use Illuminate\Database\Eloquent\Model;

// ===== Supporting Models =====
class OAPAvailability extends Model
{
    protected $table = 'oap_availability';
    protected $fillable = ['oap_id', 'date', 'start_time', 'end_time', 'is_available', 'reason'];
    protected $casts = ['date' => 'date', 'is_available' => 'boolean'];
}
