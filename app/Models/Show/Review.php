<?php

namespace App\Models\Show;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// ===== Review Model =====
class Review extends Model
{
    use HasFactory;

    protected $table = 'show_reviews';

    protected $fillable = ['show_id', 'user_id', 'rating', 'review', 'is_approved'];

    protected $casts = ['is_approved' => 'boolean'];

    public function show()
    {
        return $this->belongsTo(Show::class, 'show_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
