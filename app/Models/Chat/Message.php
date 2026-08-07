<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'chat_messages';
    protected $fillable = ['conversation_id', 'sender_id', 'body', 'edited_at'];

    protected function casts(): array { return ['edited_at' => 'datetime']; }

    public function conversation() { return $this->belongsTo(Conversation::class); }
    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
}
