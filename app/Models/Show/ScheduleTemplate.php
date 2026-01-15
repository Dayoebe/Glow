<?php

namespace App\Models\Show;

use Illuminate\Database\Eloquent\Model;



// ===== Schedule Template Model =====
class ScheduleTemplate extends Model
{
    use HasFactory;

    protected $table = 'schedule_templates';

    protected $fillable = ['name', 'description', 'type', 'schedule_data', 'is_active'];

    protected $casts = [
        'schedule_data' => 'array',
        'is_active' => 'boolean',
    ];

    public function apply($startDate, $endDate)
    {
        // Logic to apply template to date range
        // Creates schedule slots based on template data
    }
}