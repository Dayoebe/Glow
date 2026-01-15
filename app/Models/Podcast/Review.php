<?php

namespace App\Models\Podcast;

use Illuminate\Database\Eloquent\Model;



class Review extends Model
{
    protected $table = 'podcast_reviews';
    protected $fillable = ['show_id', 'user_id', 'rating', 'review', 'is_approved', 'helpful_count'];
    protected $casts = ['is_approved' => 'boolean'];
    
    public function show() { return $this->belongsTo(Show::class, 'show_id'); }
    public function user() { return $this->belongsTo(User::class); }
}