<?php

namespace App\Models\Show;

use Illuminate\Database\Eloquent\Model;

// ===== Guest Model =====
class Guest extends Model
{
    use HasFactory;

    protected $table = 'show_guests';

    protected $fillable = [
        'name', 'title', 'organization', 'bio', 'photo', 'email', 
        'phone', 'social_media', 'notes'
    ];

    protected $casts = ['social_media' => 'array'];

    public function episodes()
    {
        return $this->belongsToMany(Episode::class, 'episode_guests', 'guest_id', 'episode_id')
            ->withPivot('role', 'appearance_order', 'notes');
    }

    public function getAppearanceCountAttribute()
    {
        return $this->episodes()->count();
    }
}
