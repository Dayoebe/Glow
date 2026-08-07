<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'chat_conversations';

    protected $fillable = ['type', 'created_by', 'title', 'direct_key', 'priority', 'is_pinned', 'last_message_at'];

    protected function casts(): array
    {
        return ['is_pinned' => 'boolean', 'last_message_at' => 'datetime'];
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function participants() { return $this->belongsToMany(User::class, 'chat_participants')->withPivot(['joined_at', 'last_read_at', 'is_muted'])->withTimestamps(); }
    public function messages() { return $this->hasMany(Message::class, 'conversation_id'); }
    public function latestMessage() { return $this->hasOne(Message::class, 'conversation_id')->latestOfMany(); }
}
