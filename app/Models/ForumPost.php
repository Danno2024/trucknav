<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = ['thread_id', 'user_id', 'content', 'is_edited'];

    protected $casts = [
        'is_edited' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::created(function (ForumPost $post) {
            $post->thread->increment('replies_count');
            $post->thread->update(['last_post_at' => now()]);
        });

        static::deleted(function (ForumPost $post) {
            $post->thread->decrement('replies_count');
        });
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(ForumThread::class, 'thread_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
