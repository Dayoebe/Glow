<?php

namespace App\Models\Podcast;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    protected $table = 'podcast_comments';
    protected $fillable = [
        'episode_id',
        'user_id',
        'parent_id',
        'author_name',
        'author_email',
        'comment',
        'timestamp',
        'is_approved',
        'likes',
    ];
    protected $casts = ['is_approved' => 'boolean', 'likes' => 'integer'];
    
    public function episode() { return $this->belongsTo(Episode::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function replies() { return $this->hasMany(Comment::class, 'parent_id')->latest(); }
}
